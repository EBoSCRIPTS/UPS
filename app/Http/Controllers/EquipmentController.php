<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInformationModel;
use App\Models\Equipment\EquipmentAssignmentModel;
use App\Models\Equipment\EquipmentTypeModel;
use App\Models\Equipment\EquipmentItemsModel;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function showRegistered(): \Illuminate\View\View
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

    public function addEquipmentType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|unique:equipment_type,name|max:100',
        ]);

        $add = new EquipmentTypeModel([
            'name' => $request->input('type'),
        ]);
        $add->save();

        return back()->with('success', 'Type of equipment added successfully');
    }

    public function addEquipment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|exists:equipment_type,id',
            'name' => 'string|required|max:100',
            'serial_number' => 'string|required|max:50|unique:equipment_items,serial_number',
        ]);

        $newEquipment = new EquipmentItemsModel([
            'type_id' => $request->input('type'),
            'name' => $request->input('name'),
            'serial_number' => $request->input('serial_number'),
            'is_assigned' => 0
        ]);

        $newEquipment->save();

        return back()->with('success', 'Equipment added successfully');
    }

    public function assignEquipment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee' => 'required|exists:employee_information,id',
            'equipment' => 'required|exists:equipment_items,id',
        ]);

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

    //method used in equipment_assignment page
    public function loadAssignables(Request $request): \Illuminate\View\View
    {
        $employee = new EmployeeInformationController();
        $employee = $employee->getAllEmployees();

        $equipment = EquipmentItemsModel::query()->where('is_assigned', 0)->get();

        if($request->has('employee')) { //instead of making a new method we handle equipment for user request her(get equipment for user section)
            $user = EquipmentAssignmentModel::query()->where('employee_id', $request->input('employee'))->get();

            return view('equipment_assignment', ['employees' => $employee, 'equipments' => $equipment, 'assignments' => $user, 'employeeFor' => $request->input('employee')]);
        }

        return view('equipment_assignment', ['employees' => $employee, 'equipments' => $equipment]);
    }

    public function deleteEquipment(Request $request): RedirectResponse
    {
        $equipment = EquipmentItemsModel::query()->where('id', $request->input('id'))->first();
        $equipment->delete();

        return back()->with('success', 'Item deleted!');
    }

    public function returnEquipment(Request $request): RedirectResponse
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
