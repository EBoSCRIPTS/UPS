<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksStatusModel extends Model
{
    use HasFactory;

    protected $table = 'tasks_status';

    protected $fillable = [
        'status_name',
        'project_id',
    ];

    public $timestamps = false;

}
