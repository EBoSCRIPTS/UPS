<?php

namespace App\Models\Tasks;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'assigned_to',
        'status_key',
        'priority',
        'is_completed',
    ];

    public function userMade(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'made_by', 'id');
    }

    public function userTo(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'assigned_to', 'id');
    }

    public function projectName(): BelongsTo
    {
        return $this->belongsTo(TasksProjectModel::class, 'project_id', 'id');
    }
}
