<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
</head>
<body>

<table id="example1" class="table table-bordered table-hover" border="1" style="width:600px;">
    <tbody>
    <tr>
        <td colspan="2"><div align="center">{{$prescriptionDetails['HospitalProfile'][0]->hospital_name}}</div></td>
    </tr>
    <tr>
        <td>Doctor's Name</td>
        <td>{{$prescriptionDetails['DoctorProfile'][0]->name}}</td>
    </tr>
    <tr>
        <td>Patient's Name</td>
        <td>{{$prescriptionDetails['PatientProfile'][0]->name}}</td>
    </tr>
    <tr>
        <td>Date of Issues</td>
        <td>{{date("Y-m-d")}}</td>
    </tr>
    <tr>
        <td colspan="2"><div align="center">Pharmacy - Medicines</div></td>
    </tr>
    <tr>
        <td colspan="2">
            <table id="example2" class="table table-bordered table-hover"  border="1" style="width:100%">
                <thead>
                <tr>
                    <th>Brand</th>
                    <th>Drug</th>
                    <th>Dosage</th>
                    <th>Days</th>
                    <th>Frequency</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($prescriptionDetails['PatientDrugDetails'] as $prescription)
                    <tr>
                        <td>{{$prescription->brand_name}}</td>
                        <td>{{$prescription->drug_name}}</td>
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

</body>
</html>