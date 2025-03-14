<?php

namespace App\Services;

use GuzzleHttp\Client;

class FluxCDService
{
    protected $client;
    protected $gitRepository;
    protected $gitBranch;
    protected $gitToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->gitRepository = env('FLUXCD_GIT_REPOSITORY');
        $this->gitBranch = env('FLUXCD_GIT_BRANCH', 'main');
        $this->gitToken = env('FLUXCD_GIT_TOKEN');
    }

    public function deployApplication(array $appData)
    {
        // Create or update the Kubernetes manifest in the Git repository
        $manifest = $this->generateManifest($appData);
        $this->commitManifestToGit($manifest, $appData['appName']);
    }

    protected function generateManifest(array $appData)
    {
        // Generate the Kubernetes manifest YAML for the application
        return [
            'apiVersion' => 'apps/v1',
            'kind' => 'Deployment',
            'metadata' => [
                'name' => $appData['appName'],
                'namespace' => $appData['namespace'] ?? 'default',
            ],
            'spec' => [
                'replicas' => $appData['replicas'] ?? 1,
                'selector' => [
                    'matchLabels' => [
                        'app' => $appData['appName'],
                    ],
                ],
                'template' => [
                    'metadata' => [
                        'labels' => [
                            'app' => $appData['appName'],
                        ],
                    ],
                    'spec' => [
                        'containers' => [
                            [
                                'name' => $appData['appName'],
                                'image' => $appData['image'],
                                'ports' => [
                                    ['containerPort' => $appData['port'] ?? 80],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function commitManifestToGit(array $manifest, string $appName)
    {
        // Convert the manifest array to YAML
        $manifestYaml = yaml_emit($manifest);

        // Commit the manifest to the Git repository
        $filePath = "kustomize/overlays/{$appName}/deployment.yaml";
        $commitMessage = "Add/Update deployment for {$appName}";

        // Use GitHub API or any other Git service API to commit the file
        $this->client->request('PUT', "https://api.github.com/repos/{$this->gitRepository}/contents/{$filePath}", [
            'headers' => [
                'Authorization' => "token {$this->gitToken}",
                'Accept' => 'application/vnd.github.v3+json',
            ],
            'json' => [
                'message' => $commitMessage,
                'content' => base64_encode($manifestYaml),
                'branch' => $this->gitBranch,
            ],
        ]);
    }
}
