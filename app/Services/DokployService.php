<?php

namespace App\Services;

use GuzzleHttp\Client;

class DokployService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('DOKPLOY_API_KEY'); // Store your API key in the .env file
        $this->baseUrl = env('DOKPLOY_BASE_URL'); // Base URL of your Dokploy instance
    }

    public function createProject(array $data)
    {
        return $this->makeRequest('POST', '/project.create', $data);
    }

    public function createApplication(array $data)
    {
        return $this->makeRequest('POST', '/application.create', $data);
    }

    public function updateApplication($appId, array $data)
    {
        return $this->makeRequest('PUT', "/application.update/{$appId}", $data);
    }

    public function getProjectApplications($projectId)
    {
        return $this->makeRequest('GET', "/project.applications/{$projectId}");
    }

    public function getApplicationDetails($appId)
    {
        return $this->makeRequest('GET', "/application.details/{$appId}");
    }

    public function deleteApplication($appId)
    {
        return $this->makeRequest('DELETE', "/application.delete/{$appId}");
    }

    protected function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $response = $this->client->request($method, $this->baseUrl . $endpoint, [
                'headers' => [
                    'accept' => 'application/json',
                    'x-api-key' => $this->apiKey,
                ],
                'json' => $data,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            // Handle exception (e.g., log the error, rethrow, etc.)
            throw new \Exception("Dokploy API request failed: " . $e->getMessage());
        }
    }
}
