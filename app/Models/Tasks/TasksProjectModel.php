<?php

namespace App\Models\Tasks;

use App\Models\DepartmentsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TasksProjectModel extends Model
{
    use HasFactory;

    protected $table = 'tasks_project';

    protected $fillable = [
      'name',
      'department_id',
    ];

    public $timestamps = false;

    public function department(): BelongsTo
    {
        return $this->belongsTo(DepartmentsModel::class, 'department_id', 'id');
    }

}
