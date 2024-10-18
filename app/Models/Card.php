<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'folder_id',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'card_tags');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getImageUrlAttribute()
    {
        if(!is_null($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        } else {
            return null;
        }
    }
}
