<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Accountant Payslips</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</home>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Payslip preview
                            @if($isFullfilled == true)
                                <span class="badge bg-success" id="status">Fullfilled</span>
                                @else
                                <span class="badge bg-danger" id="status">Not fullfilled</span>
                                @endif
                            </h3>
                        </div>
                        <div class="col-md-6">
                           <h3 class="float-end" contenteditable="true">Issued date: ->input here<-</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Info</th>
                                        <th scope="col">Hours</th>
                                        <th scope="col">Amount</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Base monthly salary before taxes</td>
                                        <td>{{$hours->total_hours}}</td>
                                        <td style="color: green">{{$baseSalary}}</td>
                                    </tr>
                                    <tr>
                                        <td>Extra pay during night hours</td>
                                        <td>{{$hours->night_hours}}</td>
                                        <td style="color: green">{{$nightSalary}}</td>
                                    </tr>
                                    <tr>
                                        <td>Overtime hours</td>
                                        <td>{{$overtimeHours}}</td>
                                        <td style="color: green">{{$overtimeSalary}}</td>
                                    </tr>
                                    @foreach($taxes as $tax)
                                        <tr>
                                            <td>{{$tax['tax_name']}}</td>
                                            <td></td>
                                            <td style="color: red">-{{round(($tax['tax_rate'] / 100) * ($baseSalary + $nightSalary + $overtimeSalary), 2)}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>TOTAL:</td>
                                        <td></td>
                                        <td><strong style="color: green">{{($baseSalary + $nightSalary + $overtimeSalary) - ($baseSalary + $nightSalary + $overtimeSalary) * 0.3}}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Employee name: {{$employee->user->first_name}} {{$employee->user->last_name}}</h4>
                            <hr>
                            <h4>Department: {{$employee->department->name}}</h4>
                            <h4>Position: {{$employee->position}}</h4>
                            <hr>
                            <h5>To be paid to: {{$employee->bank_account_name}}</h5>
                            <h5>Account number: {{$employee->bank_account}}</h5>
                            <hr>
                            <h4 contenteditable="true">Payment for period: HERE</h4>
                            @if($employee->hour_pay != null)
                            <small>Base pay per hour: {{$employee->hour_pay}}</small>
                                <br>
                            @else
                                <small>Set monthly salary: {{$employee->salary}}</small>
                            @endif
                            <small>Made by: {{Auth::user()->first_name}} {{Auth::user()->last_name}}</small>
                            <br>
                            <small>Base weekly hours amount does not include overtime or extra nighthour pay</small>
                            <br>
                            <button type="button" class="btn btn-success btn-sm" id="printButton" onclick="printPayslip()">Print</button>
                            @if($isFullfilled == false)
                            <a href="/accountant/payslip/{{$employee->department->id}}/{{$employee->id}}/{{\Carbon\Carbon::now()->year}}/{{$month}}/{{$hours->id}}/fulfill" class="btn btn-primary btn-sm" id="fulfill">Fulfill</a>
                            @endif
                        </div>
                    </div>
            </div>
        </div>
    </div>

</div>
</body>

<script>
    function printPayslip(){
        const sidebar = document.getElementById('sidebar');
        const printButton = document.getElementById('printButton');
        const status = document.getElementById('status');
        const fulfill = document.getElementById('fulfill')
        fulfill.style.display = 'none';
        status.style.display = 'none';
        printButton.style.display = 'none';
        sidebar.style.display = 'none';
        window.print();
        window.location.reload();
    }
</script>
