<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTopicModel extends Model
{
    use HasFactory;

    protected $table = 'news_topic';

    protected $fillable = [
        'topic',
        'text',
        'about',
        'news_image'
    ];
}
