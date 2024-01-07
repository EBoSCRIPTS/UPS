<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCommentsRatingModel extends Model
{
    use HasFactory;

    protected $table = 'news_comments_rating';

    protected $fillable = [
        'comment_id',
        'news_topic_id',
        'user_id',
        'agree',
        'disagree'
    ];
}
