<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksTaskCommentsModel extends Model
{
    use HasFactory;

    protected $table = 'tasks_task_comments';

    protected $fillable = [
        'task_id',
        'comment_author_id',
        'comment_text',
    ];
}
