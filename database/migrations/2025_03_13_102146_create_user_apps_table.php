<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_apps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('app_id')->constrained()->onDelete('cascade');
            $table->string('project_id')->nullable();
            $table->string('deployment_id')->nullable();
            $table->string('status')->default('pending'); // deploying, running, failed
            $table->string('url')->nullable();
            $table->json('resources')->nullable();
            $table->json('env_variables')->nullable();
            $table->text('logs')->nullable();
            $table->json('monitoring')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_apps');
    }
};
