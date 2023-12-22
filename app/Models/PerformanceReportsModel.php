<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReportsModel extends Model
{
    use HasFactory;

    protected $table = 'performance_reports';

    protected $fillable = [
        'project_id',
        'user_id',
        'employee_name',
        'rating_text',
        'rating',
        'year',
        'month',
        'soft_deleted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
