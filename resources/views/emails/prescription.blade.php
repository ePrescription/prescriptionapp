<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">

    <section class="content">
        <div class="row">

            <div class="col-xs-6">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Patient Details</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <div class="col-md-12">
                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label">Patient ID</label>
                                <div class="col-sm-6">
                                    {{$prescriptionDetails['PatientProfile'][0]->pid}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label">Patient Name</label>
                                <div class="col-sm-6">
                                    {{$prescriptionDetails['PatientProfile'][0]->name}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label">Phone Number</label>
                                <div class="col-sm-6">
                                    {{$prescriptionDetails['PatientProfile'][0]->telephone}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label">E-Mail</label>
                                <div class="col-sm-6">
                                    {{$prescriptionDetails['PatientProfile'][0]->email}}
                                </div>
                            </div>




                        </div>
                        <div class="col-md-1"></div>


                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
            <div class="col-xs-6">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Doctor Details</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <div class="col-md-12">
                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label">Doctor ID</label>
                                <div class="col-sm-6">
                                    {{$prescriptionDetails['DoctorProfile'][0]->did}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label">Doctor Name</label>
                                <div class="col-sm-6">
                                    {{$prescriptionDetails['DoctorProfile'][0]->name}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label">Phone Number</label>
                                <div class="col-sm-6">
                                    {{$prescriptionDetails['DoctorProfile'][0]->telephone}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-sm-6 control-label">E-Mail</label>
                                <div class="col-sm-6">
                                    {{$prescriptionDetails['DoctorProfile'][0]->email}}
                                </div>
                            </div>




                        </div>
                        <div class="col-md-1"></div>


                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Drugs Details</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>BRAND</th>
                                <th>DRUG</th>
                                <th>DOSAGE</th>
                                <th>DAYS</th>
                                <th>Morning - Afternoon - Night</th>
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
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

</div>

</body>
</html>