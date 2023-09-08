<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'achievement_type_id',
        'achievement_value',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
