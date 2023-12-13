<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInformationModel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Equipment\EquipmentAssignmentModel;
use App\Models\Equipment\EquipmentItemsModel;

class PDFController extends Controller
{
    public function generateEquipmentAgreement(Request $request)
    {
        $employee = $request->input('employee');
        $employeeData = json_decode($employee);
        $getUserEquipment = EquipmentAssignmentModel::query()->where('employee_id', $employeeData->id)->get()->toArray();

        $userEquipment = [];

        for($i = 0; $i < sizeof($getUserEquipment); $i++){
            $userEquipment[$i] = $getUserEquipment[$i]['equipment_id'];
        }

        $eqName = [];

        for($i = 0; $i < sizeof($userEquipment); $i++)
        {
            $eqName[$i] = EquipmentItemsModel::query()->where('id', $userEquipment[$i])->select('name', 'serial_number')->first()->toArray();
        }

        $data = [
            'title' => 'Equipment Agreement',
            'date' => Carbon::now(),
            'employee' => $employeeData->user->first_name . ' ' . $employeeData->user->last_name,
            'equipment' => $eqName,
        ];

        $pdf = PDF::loadView('PDF.EquipmentAgreement', $data);

        return $pdf->download();
    }
}
