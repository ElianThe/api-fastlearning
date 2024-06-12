<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFolder extends Model
{
    use HasFactory;

    protected $table = 'users_folders';
    protected $fillable = [
        'user_id',
        'folder_id'
    ];
}
