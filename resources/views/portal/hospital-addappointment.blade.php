@extends('layout.master-hospital-inner')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
@stop
<?php
$dashboard_menu="0";
$patient_menu="1";
$profile_menu="0";
?>
<?php
$time_array=array(
        '00:00:00'=>'12:00 AM','00:15:00'=>'12:15 AM','00:30:00'=>'12:30 AM','00:45:00'=>'12:45 AM',
        '01:00:00'=>'01:00 AM','01:15:00'=>'01:15 AM','01:30:00'=>'01:30 AM','01:45:00'=>'01:45 AM',
        '02:00:00'=>'02:00 AM','02:15:00'=>'02:15 AM','02:30:00'=>'02:30 AM','02:45:00'=>'02:45 AM',
        '03:00:00'=>'03:00 AM','03:15:00'=>'03:15 AM','03:30:00'=>'03:30 AM','03:45:00'=>'03:45 AM',
        '04:00:00'=>'04:00 AM','04:15:00'=>'04:15 AM','04:30:00'=>'04:30 AM','04:45:00'=>'04:45 AM',
        '05:00:00'=>'05:00 AM','05:15:00'=>'05:15 AM','05:30:00'=>'05:30 AM','05:45:00'=>'05:45 AM',
        '06:00:00'=>'06:00 AM','06:15:00'=>'06:15 AM','06:30:00'=>'06:30 AM','06:45:00'=>'06:45 AM',
        '07:00:00'=>'07:00 AM','07:15:00'=>'07:15 AM','07:30:00'=>'07:30 AM','07:45:00'=>'07:45 AM',
        '08:00:00'=>'08:00 AM','08:15:00'=>'08:15 AM','08:30:00'=>'08:30 AM','08:45:00'=>'08:45 AM',
        '09:00:00'=>'09:00 AM','09:15:00'=>'09:15 AM','09:30:00'=>'09:30 AM','09:45:00'=>'09:45 AM',
        '10:00:00'=>'10:00 AM','10:15:00'=>'10:15 AM','10:30:00'=>'10:30 AM','10:45:00'=>'10:45 AM',
        '11:00:00'=>'11:00 AM','11:15:00'=>'11:15 AM','11:30:00'=>'11:30 AM','11:45:00'=>'11:45 AM',
        '12:00:00'=>'12:00 PM','12:15:00'=>'12:15 PM','12:30:00'=>'12:30 PM','12:45:00'=>'12:45 PM',
        '13:00:00'=>'01:00 PM','13:15:00'=>'01:15 PM','13:30:00'=>'01:30 PM','13:45:00'=>'01:45 PM',
        '14:00:00'=>'02:00 PM','14:15:00'=>'02:15 PM','14:30:00'=>'02:30 PM','14:45:00'=>'02:45 PM',
        '15:00:00'=>'03:00 PM','15:15:00'=>'03:15 PM','15:30:00'=>'03:30 PM','15:45:00'=>'03:45 PM',
        '16:00:00'=>'04:00 PM','16:15:00'=>'04:15 PM','16:30:00'=>'04:30 PM','16:45:00'=>'04:45 PM',
        '17:00:00'=>'05:00 PM','17:15:00'=>'05:15 PM','17:30:00'=>'05:30 PM','17:45:00'=>'05:45 PM',
        '18:00:00'=>'06:00 PM','18:15:00'=>'06:15 PM','18:30:00'=>'06:30 PM','18:45:00'=>'06:45 PM',
        '19:00:00'=>'07:00 PM','19:15:00'=>'07:15 PM','19:30:00'=>'07:30 PM','19:45:00'=>'07:45 PM',
        '20:00:00'=>'08:00 PM','20:15:00'=>'08:15 PM','20:30:00'=>'08:30 PM','20:45:00'=>'08:45 PM',
        '21:00:00'=>'09:00 PM','21:15:00'=>'09:15 PM','21:30:00'=>'09:30 PM','21:45:00'=>'09:45 PM',
        '22:00:00'=>'10:00 PM','22:15:00'=>'10:15 PM','22:30:00'=>'10:30 PM','22:45:00'=>'10:45 PM',
        '23:00:00'=>'11:00 PM','23:15:00'=>'11:15 PM','23:30:00'=>'11:30 PM','23:45:00'=>'11:45 PM',
);

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
            <h1>Add Patient Appointment</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Add Patient Appointment</li>
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
                            <h3 class="box-title" style="line-height:30px;">Patient Appointment</h3>
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

                                <form action="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/patient/{{$patientProfile[0]->patient_id}}/saveappointment" role="form" method="POST">
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
                                        {{--
                                        <input type="time" class="form-control" name="appointmentTime" value="" required="required" />
                                        --}}
                                        <select class="form-control" name="appointmentTime" required="required">

                                            <option value=""> --:-- -- </option>
                                            @foreach($time_array as $time_value)
                                            <?php $key=array_keys($time_array,$time_value); ?>
                                            <option value="{{$key[0]}}"> {{$time_value}} </option>
                                            @endforeach

                                        </select>

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
