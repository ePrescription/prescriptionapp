@extends('layout.master-patient')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
@stop
<?php
$dashboard_menu="0";
$prescription_menu="1";
$lab_menu="0";
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
            <h1>Prescriptions List</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Prescriptions List</li>
            </ol>
            <?php //print_r($prescriptions); ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Prescriptions Details List</h3>
                            <!--
                            <a href="doctorcreate.html" style="float:right;"><button type="submit" class="btn btn-success"><i class="fa fa-edit"></i><b> Create New Doctor</b></button></a>
                            -->
                            <form action="{{URL::to('/')}}/patient/rest/api/prescription" method="get" style="line-height:30px;width:500px;float:left;">
                                <div class="col-xs-3">
                                Search By PRID
                                </div>
                                <div class="col-xs-5">
                                <input name="prid" id="prid" class="form-control" type="text" value="" />
                                </div>
                                <div class="col-xs-4">
                                    <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block btn-flat">SEARCH</button>
                                </div>
                            </form>
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
                                @foreach($prescriptions as $prescription)
                                <tr>
                                    <td>{{$prescription->prid}}</td>
                                    <td>{{$prescription->pid}}</td>
                                    <td>{{$prescription->name}}</td>
                                    <td>{{$prescription->prescription_date}}</td>
                                    <td>

                                        <a href="{{URL::to('/')}}/patient/rest/api/prescription/{{$prescription->prescription_id}}"><button type="submit" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</button></a>

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

</body>
</html>
