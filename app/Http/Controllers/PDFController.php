<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInformationModel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Equipment\EquipmentAssignmentModel;

class PDFController extends Controller
{
    public function generateEquipmentAgreement(Request $request)
    {
        $getUserEquipment = EquipmentAssignmentModel::query()->where('employee_id', $request->input('employee'))->get();
        $employee = EmployeeInformationModel::query()->where('id', $request->input('employee'))->first();

        $data = [
            'title' => 'Equipment Agreement',
            'date' => Carbon::now(),
            'employee' => $employee,
            'equipment' => $getUserEquipment,
        ];

        $pdf = PDF::loadView('PDF.EquipmentAgreement', $data);

        return $pdf->download();
    }
}
