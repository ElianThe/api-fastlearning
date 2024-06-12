<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolderTreeFolders extends Model
{
    use HasFactory;

    protected $table = 'folder_tree_folders';

    protected $fillable = [
        'name',
        'folder_id',
        'parent_id',
        'type'
    ];

    public function folders()
    {
        $this->belongsTo(Folder::class, 'folder_id', 'id');
    }
}
