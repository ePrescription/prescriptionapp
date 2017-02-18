<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
</head>
<body>

<table id="example1" class="table table-bordered table-hover" style="width:600px; border:1px solid; border-color:darkgray; color:#000; padding:10px">
    <tbody>
        <tr>
            <td colspan="2"><div align="center">{{$labTestDetails['HospitalProfile'][0]->hospital_name}}</div></td>
        </tr>
        <tr>
            <td>Doctor's Name</td>
            <td>{{$labTestDetails['DoctorProfile'][0]->name}}</td>
        </tr>
        <tr>
            <td>Patient's Name</td>
            <td>{{$labTestDetails['PatientProfile'][0]->name}}</td>
        </tr>
        <tr>
            <td>Date of Issues</td>
            <td>{{date("Y-m-d")}}</td>
        </tr>
        <tr>
            <td colspan="2"><div align="center" style="margin:10px; text-decoration: underline; font-weight:bold">Lab Tests</div></td>
        </tr>
        <tr>
            <td colspan="2">
                <table id="example2" class="table table-bordered table-hover" style="width:100%; border-color:darkgray; color:#000;">
                    <thead>
                    <tr style="color:#fff; background: #3276b1;">
                        <th style="font-weight:normal; padding:6px;">Test Name</th>
                        <th style="font-weight:normal; padding:6px;">Test Details</th>
                        <th style="font-weight:normal; padding:6px;">Status</th>
                        <!-- <th style="font-weight:normal; padding:6px;">Result</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($labTestDetails['PatientLabTestDetails'] as $labtest)
                        <tr>
                            <td>{{$labtest->test_name}}</td>
                            <td>{{$labtest->brief_description}}</td>
                            <td>Open</td>
                            <!-- <td>
                                @if(!is_null($labtest->labtest_report))
                                    <a target="_blank" href="{{$labtest->labtest_report}}">Download</a>
                                @else
                                    None
                                @endif
                            </td> -->
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