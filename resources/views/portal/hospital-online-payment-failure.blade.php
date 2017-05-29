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
            <h1>Online Payment Failure</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Online Payment Failure</li>
            </ol>

        </section>


        <!-- Main content -->
        <section id="PagePrintX" class="content">
            <div class="row">


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


            </div><!-- /.row -->
        </section><!-- /.content -->


    </div><!-- /.content-wrapper -->


    @include('portal.hospital-footer')
</div><!-- ./wrapper -->


@endsection
