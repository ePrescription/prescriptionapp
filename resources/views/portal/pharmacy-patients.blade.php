@extends('layout.master-pharmacy-inner')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
@stop
<?php
$dashboard_menu="0";
$prescription_menu="0";
$patient_menu="1";
$profile_menu="0";
?>
@section('content')
<div class="wrapper">
    @include('portal.pharmacy-header')
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        @include('portal.pharmacy-sidebar')
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Pharmacy Patients List</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Pharmacy Patients List</li>
            </ol>
            <?php //print_r($patients); ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;">Pharmacy Patients Details List</h3>
                            <!--
                            <a href="doctorcreate.html" style="float:right;"><button type="submit" class="btn btn-success"><i class="fa fa-edit"></i><b> Create New Doctor</b></button></a>
                            -->
                        </div><!-- /.box-header -->
                        <div class="box-body">
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
                                    <th></th>
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
    @include('portal.pharmacy-footer')
</div><!-- ./wrapper -->

@endsection
