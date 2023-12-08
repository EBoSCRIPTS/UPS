<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksTaskModel extends Model
{
    use HasFactory;

    protected $table = 'tasks_task';

    protected $fillable = [
        'department_id',
        'project_id',
        'title',
        'description',
        'made_by',
        'assigned_to'
    ];
}
