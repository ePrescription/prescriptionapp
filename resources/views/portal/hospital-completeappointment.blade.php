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
            <h1>Complete Patient Appointment</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Complete Patient Appointment</li>
            </ol>
            <?php //print_r($pharmacyProfile); ?>
            <?php //print_r($cities); ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;">Complete Patient Appointment</h3>
                            <!--
                            <a href="doctorcreate.html" style="float:right;"><button type="submit" class="btn btn-success"><i class="fa fa-edit"></i><b> Create New Doctor</b></button></a>
                            -->
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            @if (session()->has('message'))
                                <div class="col_full login-title">
                                <span style="color:red;">
                                    <b>{{session('message')}}</b>
                                </span>
                                </div>
                            @endif

                            @if (session()->has('success'))
                                <div class="col_full login-title">
                                <span style="color:green;">
                                    <b>{{session('success')}}</b>
                                </span>
                                </div>
                                @endif
                                        <!-- form start -->

                            <form action="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/patient/{{$patientProfile[0]->patient_id}}/saveappointment" role="form" method="POST" class="hidden">
                            <input type="hidden" name="hospitalId" value="{{Auth::user()->id}}" required="required" />
                            <input type="hidden" name="patientId" value="{{$patientProfile[0]->patient_id}}" required="required" />

                            <div class="col-md-12">
                                <style>.control-label{line-height:32px;}</style>

                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Patient Name</label>
                                    <div class="col-sm-9">
                                        {{$patientProfile[0]->name}}-{{$patientProfile[0]->pid}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Patient Mobile</label>
                                    <div class="col-sm-9">
                                        {{$patientProfile[0]->telephone}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Patient Email</label>
                                    <div class="col-sm-9">
                                        {{$patientProfile[0]->email}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Doctor Name</label>
                                    <div class="col-sm-9">

                                        <select name="doctorId" class="form-control" required="required" >
                                        <option value="">--CHOOSE--</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{$doctor->doctorId}}">{{$doctor->doctorName.' '.$doctor->doctorUniqueId}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Appointment Created Date</label>
                                    <div class="col-sm-9">
                                        {{date('Y-m-d')}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Appointment Date</label>
                                    <div class="col-sm-9">
                                        <input type="date" data-date-format="YYYY-MM-DD" min="{{date('Y-m-d')}}" class="form-control" name="appointmentDate" value="" required="required" />
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Appointment Time</label>
                                    <div class="col-sm-9">
                                        <input type="time" class="form-control" name="appointmentTime" value="" required="required" />
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Brief History</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="briefHistory" required="required"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-success" style="float:right;">Save Appointment</button>
                                </div>

                            </form>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->


                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('portal.hospital-footer')
</div><!-- ./wrapper -->

@endsection
