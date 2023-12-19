<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInformationModel;
use App\Models\Equipment\EquipmentAssignmentModel;
use App\Models\Equipment\EquipmentTypeModel;
use App\Models\Equipment\EquipmentItemsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function showRegistered()
    {
        $equipment = EquipmentItemsModel::query()->where('is_assigned', 1)->get();

        $notAssigned = EquipmentItemsModel::query()->where('is_assigned', 0)->get();
        $type = EquipmentTypeModel::all();

        $assignedArray = [];
        $nameArray = [];
        foreach($equipment as $eq)
        {
            $assignedTo = EquipmentAssignmentModel::query()->where('equipment_id', $eq->id)
                ->with('employee.user')
                ->pluck('employee_id')->first();
            $assignedArray[$eq->id] = $assignedTo;
        }

        foreach($assignedArray as $key => $value)
        {
            $employeeInformation = EmployeeInformationModel::query()->where('id', $value)->first();
            $fullName = $employeeInformation->user->first_name . ' ' . $employeeInformation->user->last_name;
            $nameArray[$key] = $fullName;
        }

        return view('equipment_registration',
            ['equipments' => $equipment,
            'types' => $type,
            'availableEquipments' => $notAssigned,
            'assignedEquipment' => $nameArray]);
    }

    public function addEquipmentType(Request $request)
    {
        $add = new EquipmentTypeModel([
            'name' => $request->input('type'),
        ]);
        $add->save();

        return back()->with('success', 'Type of equipment added successfully');
    }

    public function addEquipment(Request $request)
    {
        $newEquipment = new EquipmentItemsModel([
            'type_id' => $request->input('type'),
            'name' => $request->input('name'),
            'serial_number' => $request->input('serial_number'),
            'is_assigned' => 0
        ]);

        $newEquipment->save();

        return back()->with('success', 'Equipment added successfully');
    }

    public function assignEquipment(Request $request)
    {
        $employee = EquipmentAssignmentModel::query()->where('employee_id', $request->input('employee_id'))->first();

        $give = new EquipmentAssignmentModel([
            'employee_id' => $request->input('employee'),
            'equipment_id' => $request->input('equipment'),
            'date_given' => Carbon::now(),
        ]);

        $givenEquipment = EquipmentItemsModel::query()->where('id', $request->input('equipment'))->first();
        $givenEquipment->update([
            'is_assigned' => 1
        ]);

        $give->save();

        return back()->with('success', 'Equipment assigned successfully');
    }

    public function loadAssignables(Request $request)
    {
        $employee = new EmployeeInformationController();
        $employee = $employee->getAllEmployees();

        $equipment = EquipmentItemsModel::query()->where('is_assigned', 0)->get();

        if($request->has('employee')) {
            $user = EquipmentAssignmentModel::query()->where('employee_id', $request->input('employee'))->get();
            return view('equipment_assignment', ['employees' => $employee, 'equipments' => $equipment, 'assignments' => $user, 'employee' => $request->input('employee')]);
        }

        return view('equipment_assignment', ['employees' => $employee, 'equipments' => $equipment]);
    }

    public function deleteEquipment(Request $request)
    {
        $equipment = EquipmentItemsModel::query()->where('id', $request->input('id'))->first();
        $equipment->delete();

        return back()->with('success', 'Item deleted!');
    }

    public function returnEquipment(Request $request)
    {
        $equipment = EquipmentItemsModel::query()->where('id', $request->input('id'))->first();
        $userAssignment = EquipmentAssignmentModel::query()->where('id', $request->input('assignment_id'))->first();
        $userAssignment->delete();

        $equipment->update([
            'is_assigned' => 0
        ]);

        return redirect('/equipment/equipment_assignment')->with('success', 'Item returned!');
    }


}
