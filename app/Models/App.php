<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'template_id', 'description', 'icon', 'category_id', 'default_env', 'image', 'website'];

    protected $casts = [
        'default_env' => 'array',
    ];

    public function userApps() {
        return $this->hasMany(UserApp::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
