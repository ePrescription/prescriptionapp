@extends('layout.master-hospital-inner')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
@stop
<?php
$dashboard_menu="0";
$patient_menu="1";
$profile_menu="0";
?>
@section('content')
<div class="wrapper">
    @include('portal.hospital-header')
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        @include('portal.hospital-sidebar')
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Prescriptions Details</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Prescriptions Details</li>
            </ol>
            <?php //dd($prescriptionDetails); exit; ?>
        </section>

        <!-- Main content -->

        <div style="width:98%;float: right;padding:10px;margin:10px;"><a href="" style="float: right;" ><button type="button" class="btn btn-success btn-xs" onclick="javascript:printDiv();"><i class="fa fa-print"></i> PRINT</button></a></div>
        <!-- Main content -->
        <section id="PagePrintX" class="content">
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
                                    <th>TRADE</th>
                                    <th>FORMULATION</th>
                                    <th>DOSAGE</th>
                                    <th>DAYS</th>
                                    <th>INTAKE</th>
                                    <th>Morning - Afternoon - Night</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($prescriptionDetails['PatientDrugDetails'] as $prescription)
                                    <tr>
                                        <td>{{$prescription->trade_name}}</td>
                                        <td>{{$prescription->formulation_name}}</td>
                                        <td>{{$prescription->dosage}}</td>
                                        <td>{{$prescription->no_of_days}}</td>
                                        <td>{{$prescription->intake_form}}</td>
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
                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Notes</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12">
                                <div class="form-group col-md-12">
                                    <div class="col-sm-12">
                                        @foreach($prescriptionDetails['PrescriptionInfo'] as $prescriptioninfo)
                                            {{$prescriptioninfo->notes}}
                                        @endforeach
                                    </div>
                                </div>



                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->



        <!-- PRINT OPEN -->

        <section id="PagePrint" class="content hidden" style="font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
            <div class="row">

                <div class="col-xs-12">
                    <div style="width:100%;float:left;">
                        <h2 style="padding: 0px;margin: 8px;color: #FFF; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-weight: bold;    font-size: 18px;">
                            Dr All Caps - Medical Prescription<br/>
                            {{Session::get('AuthDisplayName')}} - {{Session::get('LoginDoctorDetails')}}
                            <br/>
                            {{Session::get('LoginHospitalDetails')}}

                        </h2>
                    </div>
                </div>

                <div class="col-xs-6"  style="width:50%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Patient Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12" style="width:100%;float:left;">
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Patient ID</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['PatientProfile'][0]->pid}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Patient Name</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['PatientProfile'][0]->name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Phone Number</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['PatientProfile'][0]->telephone}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">E-Mail</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['PatientProfile'][0]->email}}
                                    </div>
                                </div>




                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>
                <div class="col-xs-6"  style="width:50%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Doctor Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12" style="width:100%;float:left;">
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Doctor ID</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['DoctorProfile'][0]->did}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Doctor Name</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['DoctorProfile'][0]->name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Phone Number</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['DoctorProfile'][0]->telephone}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">E-Mail</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
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
                        <div class="box-header" style="width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 18px;">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Drugs Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">
                            <table id="example2" class="table table-bordered table-hover" border="0" style="width:100%;border:1px solid #000;padding:5px;margin:5px;">
                                <thead>
                                <tr style="width:100%;line-height:30px;">
                                    <th  style="width:20%;    text-align: left;line-height:30px;">TRADE</th>
                                    <th  style="width:20%;    text-align: left;line-height:30px;">FORMULATION</th>
                                    <th  style="width:10%;    text-align: left;line-height:30px;">DOSAGE</th>
                                    <th  style="width:10%;    text-align: left;line-height:30px;">DAYS</th>
                                    <th  style="width:20%;    text-align: left;line-height:30px;">INTAKE</th>
                                    <th  style="width:20%;    text-align: left;line-height:30px;">FREQUENCY</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($prescriptionDetails['PatientDrugDetails'] as $prescription)
                                    <tr style="width:100%;line-height:30px;">
                                        <td>{{$prescription->trade_name}}</td>
                                        <td>{{$prescription->formulation_name}}</td>
                                        <td>{{$prescription->dosage}}</td>
                                        <td>{{$prescription->no_of_days}}</td>
                                        <td>{{$prescription->intake_form}}</td>
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

                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 18px;">Notes</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12">
                                <div class="form-group col-md-12">
                                    <div class="col-sm-12" style="line-height:30px;width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">
                                        @foreach($prescriptionDetails['PrescriptionInfo'] as $prescriptioninfo)
                                            {{$prescriptioninfo->notes}}
                                        @endforeach
                                    </div>
                                </div>



                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
        <!-- PRINT END -->


    </div><!-- /.content-wrapper -->


    @include('portal.hospital-footer')
</div><!-- ./wrapper -->


<script>
    function printDiv()
    {

        var divToPrint=document.getElementById('PagePrint');

        var newWin=window.open('','Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();

        setTimeout(function(){newWin.close();},10);

    }
</script>
</body>
</html>
