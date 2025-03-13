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
        Schema::table('user_apps', function (Blueprint $table) {
            $table->string('name')->nullable()->after('app_id'); // Add the 'name' column
            $table->text('description')->nullable()->after('name'); // Add the 'description' column
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_apps', function (Blueprint $table) {
            $table->dropColumn('name'); // Remove the 'name' column
            $table->dropColumn('description'); // Remove the 'description' column
   
        });
    }
};
