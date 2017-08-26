<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
</head>

<body>

<table id="example1" class="table table-bordered table-hover"  style="width:100%; border:1px solid; border-color:darkgray; color:#000; padding:10px">
    <tbody>
    <tr>
        <td colspan="2"><div align="center" style="font-weight:bold;">{{$prescriptionDetails['HospitalProfile'][0]->hospital_name}}</div></td>
    </tr>
    <tr>
        <td>Doctor's Name</td>
        <td style="font-weight:bold;">{{$prescriptionDetails['DoctorProfile'][0]->name}}</td>
    </tr>
    <tr>
        <td>Patient's Name</td>
        <td style="font-weight:bold;">{{$prescriptionDetails['PatientProfile'][0]->name}}</td>
    </tr>
    <tr>
        <td>Prescription Date</td>
        <td style="font-weight:bold;">{{date("Y-m-d")}}</td>
    </tr>
    <tr >
        <td colspan="2"><div align="center" style="margin:10px; text-decoration: underline; font-weight:bold">Prescribed Drugs</div></td>
    </tr>
    <tr>
        <td colspan="2">
            <table id="example2" class="table table-bordered table-hover"  style="width:100%; border-color:darkgray; color:#000;">
                <thead>
                <tr style="color:#fff; background: #3276b1;">
                    <th style="font-weight:normal; padding:6px;">Brand</th>
                    <th style="font-weight:normal; padding:6px;">Drug</th>
                    <th style="font-weight:normal; padding:6px;">Dosage</th>
                    <th style="font-weight:normal; padding:6px;">Days</th>
                    <th style="font-weight:normal; padding:6px;">Frequency</th>
                    <th style="font-weight:normal; padding:6px;">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($prescriptionDetails['PatientDrugDetails'] as $prescription)
                    <tr>
                        <td>{{$prescription->trade_name}}</td>
                        <td>{{$prescription->formulation_name}}</td>
                        <td>{{$prescription->dosage}}</td>
                        <td>{{$prescription->no_of_days}}</td>
                        <td>
                            {{$prescription->morning}} - {{$prescription->afternoon}} - {{$prescription->night}}
                        </td>
                        <td>Open</td>
                    </tr>
                @endforeach

                </tbody>

            </table>

        </td>
    </tr>
    </tbody>
</table>
<table id="example1" class="table table-bordered table-hover"  style="width:100%;  margin-top:10px; border:1px solid; border-color:darkgray; color:#000; padding:5px">
    <tbody>
        <tr>
            <td style="padding: 2px;">
                <!-- <span style="font-weight:bold; margin-bottom: 10px; padding: 2px; min-width: 600px;">Notes:</span> -->
                <div style="font-weight:bold; margin-bottom: 10px; padding: 2px;">Notes</div>
            </td>
        </tr>
        <tr>
            <td style="padding: 2px; ">
                <div>
                {{$prescriptionDetails['PrescriptionInfo'][0]->notes}}
                </div>
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>