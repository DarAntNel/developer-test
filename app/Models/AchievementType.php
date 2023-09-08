<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Achievement;

class AchievementType extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'name'
    ];

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }
}

