<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReportsModel extends Model
{
    use HasFactory;

    protected $table = 'performance_reports';

    protected $fillable = [
        'project_id',
        'employee_id',
        'employee_name',
        'rating_text',
        'rating',
        'year',
        'month',
    ];
}
