<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DokployService;
use App\Models\UserApp;

class DeployPendingApps extends Command
{
    protected $signature = 'app:deploy-pending';
    protected $description = 'Deploy pending applications using Dokploy API';
    protected $dokployService;

    public function __construct(DokployService $dokployService)
    {
        parent::__construct();
        $this->dokployService = $dokployService;
    }

    public function handle()
    {
        $projectId = env('DOKPLOY_PROJECT_ID');

        if (!$projectId) {
            $this->error('Dokploy project ID is not set in the environment variables.');
            return;
        }

        $pendingApps = UserApp::where('status', 'pending')->get();

        foreach ($pendingApps as $app) {
            $templateId = $app->app->template_id;

            // Create an application within the specified project
            $applicationData = [
                'projectId' => $projectId,
                'templateId' => $templateId,
                'name' => 'New Application',
            ];
            $application = $this->dokployService->createApplication($applicationData);

            if ($application) {
                $app->status = 'deployed';
                $app->save();
                $this->info("Application {$app->id} deployed successfully.");
            } else {
                $this->error("Failed to deploy application {$app->id}.");
            }
        }
    }
}
