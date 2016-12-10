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
            <h1>Patient Details</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Patient Details</li>
            </ol>
            <?php //print_r($prescriptionDetails);exit; ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Patient Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12">


                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient ID</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->pid}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Name</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Phone Number</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->telephone}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">E-Mail</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->email}}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Age</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->age}}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Gender</label>
                                    <div class="col-sm-6">
                                        @if($patientDetails[0]->gender==1) Male @else Female @endif
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
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Appointment Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>PATIENT</th>
                                    <th>HOSPITAL</th>
                                    <th>DOCTOR</th>
                                    <th>DATE</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patientAppointment as $appointment)
                                    <tr>
                                        <td>{{$appointment->patient_name}}</td>
                                        <td>{{$appointment->hospital_name}}</td>
                                        <td>{{$appointment->name}}</td>
                                        <td>{{$appointment->appointment_date}} {{$appointment->appointment_time}}</td>
                                        <td>Open</td>
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
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Prescription Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div>
                                PRID ( Prescription Identification) - PID ( Patient Identification)
                            </div>
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>PRID</th>
                                    <th>PID</th>
                                    <th>PATIENT</th>
                                    <th>DATE</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patientPrescriptions as $prescription)
                                    <tr>
                                        <td>{{$prescription->unique_id}}</td>
                                        <td>{{$prescription->pid}}</td>
                                        <td>{{$prescription->name}}</td>
                                        <td>{{$prescription->prescription_date}}</td>
                                        <td>

                                            <a href="{{URL::to('/')}}/fronthospital/rest/api/prescription/{{$prescription->prescription_id}}"><button type="submit" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</button></a>

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
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Lab Tests Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div>
                                PLID ( Patient LabTest Identification) - PID ( Patient Identification)
                            </div>
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>PLID</th>
                                    <th>PID</th>
                                    <th>PATIENT</th>
                                    <th>DATE</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($labTests as $labTest)
                                    <tr>
                                        <td>{{$labTest->unique_id}}</td>
                                        <td>{{$labTest->pid}}</td>
                                        <td>{{$labTest->name}}</td>
                                        <td>{{$labTest->labtest_date}}</td>
                                        <td>

                                            <a href="{{URL::to('/')}}/fronthospital/rest/api/lab/{{$labTest->labtest_id}}"><button type="submit" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</button></a>

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
    @include('portal.doctor-footer')
</div><!-- ./wrapper -->

</body>
</html>
