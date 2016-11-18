@extends('layout.master-lab-inner')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
@stop
<?php
$dashboard_menu="0";
$lab_menu="0";
$patient_menu="1";
$profile_menu="0";
?>
@section('content')
<div class="wrapper">
    @include('portal.lab-header')
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        @include('portal.lab-sidebar')
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Lab Patients List</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Lab Patients List</li>
            </ol>
            <?php //print_r($patients); ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;">Lab Patients Details List</h3>
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
                                    <th>PID</th>
                                    <th>PATIENT</th>
                                    <th>PHONE</th>
                                    <th>AGE</th>
                                    <th>GENDER</th>
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
    @include('portal.lab-footer')
</div><!-- ./wrapper -->

</body>
</html>
