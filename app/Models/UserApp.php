<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApp extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'app_id', 'name', 'description', 'deployment_id', 'project_id', 'status', 'url', 'resources', 'env_variables', 'logs', 'monitoring'];

    protected $casts = [
        'resources' => 'array',
        'env_variables' => 'array',
        'monitoring' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function app() {
        return $this->belongsTo(App::class);
    }
}
