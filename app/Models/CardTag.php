<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTag extends Model
{
    use HasFactory;

    protected $table = 'card_tags';

    protected $fillable = [
        'user_id',
        'tag_id'
    ];
}
