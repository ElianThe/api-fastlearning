<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'folder_id',
        'folder_tree_folders_id',
    ];

    public function folder()
    {
        $this->belongsTo(Folder::class, 'folder_id', 'id');
    }

    public function folder_tree_folders()
    {
        $this->belongsTo(FolderTreeFolders::class, 'folder_tree_folders_id', 'id');

    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'card_tags');
    }
}
