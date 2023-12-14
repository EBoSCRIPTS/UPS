<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCommentsModel extends Model
{
    use HasFactory;

    protected $table = 'news_comments';

    protected $fillable = [
        'name',
        'topic_id',
        'comment',
    ];
}
