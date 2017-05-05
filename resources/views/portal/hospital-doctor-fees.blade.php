@extends('layout.master-hospital-inner')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
@stop
<?php
$dashboard_menu="0";
$patient_menu="0";
$doctor_menu="1";
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
            <h1>Hospital Doctor Fees List</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Hospital Doctor Fees List</li>
            </ol>
            <?php //print_r($patients); ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;">Hospital Doctor Fees Details List</h3>

                            <a href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/addfeereceipt" style="float:right;"><button type="submit" class="btn btn-success"><i class="fa fa-edit"></i><b> Create New Bill</b></button></a>

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

                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name in Full</th>
                                    <th>Doctor Name in Full</th>
                                    <th>Doctor Fee</th>
                                    <th>View</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($feeReceipts as $fee)
                                <tr>
                                    <td>{{$fee->PID}}</td>
                                    <td>{{$fee->patientName}}</td>
                                    <td>{{$fee->doctorName}}</td>
                                    <td>{{$fee->fee}}</td>
                                    <td>
                                        <a href="{{URL::to('/')}}/fronthospital/rest/api/receipt/{{$fee->receiptId}}/details" style="float:rightx;">View Details</a>
                                        <br/>
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

</body>
</html>
