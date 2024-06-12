<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $table = 'folders';

    protected $fillable = [
        'name',
        'content',
        'is_public',
        'created_by_user',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_folders', 'folder_id', 'user_id');
    }

    public function folder_tree_folders()
    {
        return $this->hasMany(FolderTreeFolders::class, 'folder_id', 'id');
    }
}
