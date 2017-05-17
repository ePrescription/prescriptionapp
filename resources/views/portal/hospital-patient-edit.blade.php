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
            <h1>Patient Details</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Patient Details</li>
            </ol>
            <?php //print_r($prescriptionDetails);exit; ?>
        </section>

        <!-- Main content -->
        <section class="content hidden">
            <div class="row">

                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Patient Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12">


                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient ID</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->pid}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Name</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Phone Number</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->telephone}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">E-Mail</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->email}}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Age</label>
                                    <div class="col-sm-6">
                                        {{$patientDetails[0]->age}}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Gender</label>
                                    <div class="col-sm-6">
                                        @if($patientDetails[0]->gender==1) Male @else Female @endif
                                    </div>
                                </div>





                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>
           </div><!-- /.row -->
        </section><!-- /.content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <!-- <h3 class="box-title" style="line-height:30px;">Hospital Profile Details</h3> -->
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
                                <form action="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/patient/{{$patientDetails[0]->patient_id}}/update" role="form" method="POST">
                                    <input type="hidden" name="hospitalId" value="{{Auth::user()->id}}" required="required" />
                                    <input type="hidden" name="patientId" value="{{$patientDetails[0]->patient_id}}" required="required" />

                                    <div class="col-md-12">
                                        <style>.control-label{line-height:32px;}</style>

                                        <div class="form-group col-md-12">
                                            <label class="col-sm-3 control-label">PID</label>
                                            <div class="col-sm-9">
                                                {{$patientDetails[0]->pid}}
                                                <input type="hidden" class="form-control" name="patient_id" value="{{$patientDetails[0]->patient_id}}" required="required" />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="name" value="{{$patientDetails[0]->name}}" required="required" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="col-sm-3 control-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="email" value="{{$patientDetails[0]->email}}" required="required" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="col-sm-3 control-label">Mobile</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="telephone" value="{{$patientDetails[0]->telephone}}" required="required" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="col-sm-3 control-label">Age</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="age" value="{{$patientDetails[0]->age}}" required="required" />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="col-sm-3 control-label">Gender</label>
                                            <div class="col-sm-9">

                                                <input type="radio" class="form-controlx" name="gender" value="1" required="required" @if($patientDetails[0]->gender==1) checked @endif />Male
                                                &nbsp;&nbsp;
                                                <input type="radio" class="form-controlx" name="gender" value="2" required="required" @if($patientDetails[0]->gender==2) checked @endif />Female
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="col-sm-3 control-label">Relationship</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="relationship" value="{{$patientDetails[0]->relationship}}" required="required" />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="col-sm-3 control-label">Spouse Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="spouseName" value="{{$patientDetails[0]->spouseName}}" required="required" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-success" style="float:right;">Save Profile</button>
                                    </div>

                                </form>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->


                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
    @include('portal.doctor-footer')
</div><!-- ./wrapper -->

@endsection
