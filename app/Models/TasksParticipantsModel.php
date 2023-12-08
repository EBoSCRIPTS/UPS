<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksParticipantsModel extends Model
{
    use HasFactory;

    protected $table = 'tasks_participants';

    protected $fillable = [
        'project_id',
        'employee_id',
    ];
}
