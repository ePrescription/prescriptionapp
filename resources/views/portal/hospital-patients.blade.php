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
            <h1>Hospital Patients List</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Hospital Patients List</li>
            </ol>

        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <!-- <h3 class="box-title" style="line-height:30px;">Hospital Patients Details List</h3> -->

                            <a href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/addpatient" style="float:right;"><button type="submit" class="btn btn-success"><i class="fa fa-edit"></i><b> Create New Patient</b></button></a>

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

                            <div>
                                PID ( Patient Identification)
                            </div>
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name in Full</th>
                                    <th>Patient Mobile No</th>
                                    <th>Patient Age</th>
                                    <th>Patient's gender</th>
                                    <th>View</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patients as $patient)
                                <tr>
                                    <td>{{$patient->pid}}</td>
                                    <td>{{$patient->name}}</td>
                                    <td>{{$patient->telephone}}</td>
                                    <td>{{$patient->age}}</td>
                                    <td>@if($patient->gender==1) Male @else Female @endif</td>
                                    <td>
                                        <a href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/patient/{{$patient->patient_id}}/details" style="float:rightx;">View Profile</a>
                                        <br/>
                                        <a href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/patient/{{$patient->patient_id}}/edit" style="float:rightx;">Edit Profile</a>
                                        <br/>
                                        <a href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/patient/{{$patient->patient_id}}/addappointment" style="float:rightx;">Book Appointment</a>
<!--
                                        <a href="#doctorview.html"><button type="submit" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</button></a>
-->
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
    @include('portal.hospital-footer')
</div><!-- ./wrapper -->

@endsection
