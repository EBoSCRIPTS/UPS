<?php

namespace App\ExcelExport;

use App\Models\Tasks\TasksTaskModel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel as MaatwebsiteExcel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class TaskExport implements FromCollection, WithHeadings
{
    public function __construct($dateFrom, $dateTo, $project_id){
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->project_id = $project_id;
    }

    public function headings(): array
    {
        return [
            'Task ID',
            'Title',
            'Description',
            'Priority',
            'Made by',
            'Task Points',
            'Created At',
            'Updated At',
        ];
    }
    public function collection()
    {
        return TasksTaskModel::query()
            ->where('project_id', $this->project_id)
            ->where('tasks_task.updated_at', '>=', $this->dateFrom)
            ->where('tasks_task.updated_at', '<=', $this->dateTo)
            ->select('tasks_task.id', 'title', 'description', 'priority', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'), 'task_points', 'tasks_task.created_at', 'tasks_task.updated_at')
            ->join('users', 'users.id', '=', 'tasks_task.made_by')
            ->get();
    }
}
