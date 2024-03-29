<?php

namespace App\Models\Tasks;

use App\Models\EmployeeInformationModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TasksParticipantsModel extends Model
{
    use HasFactory;

    protected $table = 'tasks_participants';

    protected $fillable = [
        'project_id',
        'employee_id',
    ];

    public $timestamps = false;

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeInformationModel::class, 'employee_id', 'id');
    }

    public function projectName(): belongsTo
    {
        return $this->belongsTo(TasksProjectModel::class, 'project_id', 'id');
    }
}
