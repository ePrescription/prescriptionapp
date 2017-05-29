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
            <h1>Online Payment</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Online Payment</li>
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


                <form action="{{URL::to('/')}}/fronthospital/payment/process" role="form" method="POST">

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Patient Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-4">
                                  Patient Name
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="firstname" value="" class="form-control"  style="margin-bottom: 8px;" required />
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                Patient Email
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="email" value="" class="form-control"  style="margin-bottom: 8px;" required />
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                Patient Mobile
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="phone" value="" class="form-control"  style="margin-bottom: 8px;" required />
                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>

                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Payment Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                Payment For
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="productinfo" value="" class="form-control"  style="margin-bottom: 8px;" required />
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                Payment Amount
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="amount" value="" class="form-control"  style="margin-bottom: 8px;" required/>
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        </div><!-- /.box-body -->
                        <div class="box-body">

                            <div class="col-md-12">
                                <div class="form-group col-md-12">
                                    <input type="hidden" name="tid" value="{{time().time()}}" class="form-control"/>
                                    <input type="hidden" name="order_id" value="{{time()}}" class="form-control"/>
                                    <input type="submit" name="submit" value="PAY ONLINE" class="form-controlX btn"/>
                                </div>
                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->

                    </div><!-- /.box -->

                </div>

                </form>
            </div><!-- /.row -->
        </section><!-- /.content -->


    </div><!-- /.content-wrapper -->


    @include('portal.hospital-footer')
</div><!-- ./wrapper -->


@endsection
