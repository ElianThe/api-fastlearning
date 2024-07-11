<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    protected $fillable = [
        'name',
        'type'
    ];

    public function cards() {
        return $this->belongsToMany(Card::class, 'card_tags', 'tag_id', 'card_id');
    }
}
