@extends('layout.master-patient')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
@stop
<?php
$dashboard_menu="0";
$prescription_menu="0";
$lab_menu="1";
$patient_menu="0";
$profile_menu="0";
?>
@section('content')
<div class="wrapper">
    @include('portal.patient-header')
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        @include('portal.patient-sidebar')
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Lab Tests List</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Lab Tests List</li>
            </ol>
            <?php //print_r($labTests); ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Lab Tests Details List</h3>
                            <!--
                            <a href="doctorcreate.html" style="float:right;"><button type="submit" class="btn btn-success"><i class="fa fa-edit"></i><b> Create New Doctor</b></button></a>
                            -->
                            <form action="{{URL::to('/')}}/patient/rest/api/labtests" method="get" style="line-height:30px;width:500px;float:left;">
                                <div class="col-xs-3">
                                Search By PLID
                                </div>
                                <div class="col-xs-5">
                                <input name="lid" id="lid" class="form-control" type="text" value="" />
                                </div>
                                <div class="col-xs-4">
                                    <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block btn-flat">SEARCH</button>
                                </div>
                            </form>
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
                                    <td>{{$labTest->plid}}</td>
                                    <td>{{$labTest->pid}}</td>
                                    <td>{{$labTest->name}}</td>
                                    <td>{{$labTest->labtest_date}}</td>
                                    <td>

                                        <a href="{{URL::to('/')}}/patient/rest/api/lab/{{$labTest->labtest_id}}"><button type="submit" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</button></a>

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
    @include('portal.patient-footer')
</div><!-- ./wrapper -->

@endsection
