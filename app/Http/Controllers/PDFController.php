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
    public function generateEquipmentAgreement(Request $request) //generate equipment agreement PDF file
    {
        $validated = $request->validate([
            'employee' => 'required|exists:employee_information,id',
            'equipmentText' => 'required|string'
        ]);

        $employee = $request->input('employee');

        $getUserEquipment = EquipmentAssignmentModel::query()->where('employee_id', $employee)->get()->toArray();
        $employeeName = EmployeeInformationModel::query()->where('id', $employee)->first();

        $userEquipment = []; //create array to store employee equipment

        for ($i = 0; $i < sizeof($getUserEquipment); $i++) {
            $userEquipment[$i] = $getUserEquipment[$i]['equipment_id'];
        }

        $eqName = []; //store the names and serial number of equipment

        for ($i = 0; $i < sizeof($userEquipment); $i++) {
            $eqName[$i] = EquipmentItemsModel::query()->where('id', $userEquipment[$i])->select('name', 'serial_number')->first()->toArray();
        }

        $data = [
            'title' => 'Equipment Agreement',
            'date' => Carbon::now(),
            'employee' => $employeeName->user->first_name . ' ' . $employeeName->user->last_name,
            'equipment' => $eqName,
            'text' => $request->input('equipmentText')
        ];

        $pdf = PDF::loadView('PDF.EquipmentAgreement', $data); //call PDF creator function

        return $pdf->download(); //prompt the user with PDF download
    }
}
