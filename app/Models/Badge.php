<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'badge_value',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
