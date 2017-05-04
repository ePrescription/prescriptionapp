@extends('layout.master-hospital-inner')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
@stop
<?php
$dashboard_menu="0";
$patient_menu="0";
$profile_menu="1";
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
            <h1>Hospital Profile Details</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Hospital Profile Details</li>
            </ol>
            <?php //print_r($labProfile); ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;">Hospital Profile Details</h3>
                            <!--
                            <a href="doctorcreate.html" style="float:right;"><button type="submit" class="btn btn-success"><i class="fa fa-edit"></i><b> Create New Doctor</b></button></a>
                            -->
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12">
                                <style>.control-label{line-height:32px;}</style>
                                @if(!empty($message))
                                    <p style="color:green;">{{$message}}</p>
                                @endif
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Hospital ID</label>
                                    <div class="col-sm-9">
                                        {{$hospitalProfile[0]->hid}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Hospital Name</label>
                                    <div class="col-sm-9">
                                        {{$hospitalProfile[0]->hospital_name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Address </label>
                                    <div class="col-sm-9">
                                        {{$hospitalProfile[0]->address}}
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">City</label>
                                    <div class="col-sm-9">
                                        {{$hospitalProfile[0]->city_name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-9">
                                        {{$hospitalProfile[0]->country_name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">Phone Number</label>
                                    <div class="col-sm-9">
                                        {{$hospitalProfile[0]->telephone}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="col-sm-3 control-label">E-Mail</label>
                                    <div class="col-sm-9">
                                        {{$hospitalProfile[0]->email}}
                                    </div>
                                </div>




                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->


                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('portal.hospital-footer')
</div><!-- ./wrapper -->

@endsection
