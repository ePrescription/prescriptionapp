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
            <h1>Fees Details</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Fees Details</li>
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


                <form action="{{URL::to('/')}}/fronthospital/rest/api/savefeereceipt" role="form" method="POST">

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Patient Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-4">
                            <select name="patientId" id="patientId" class="form-control patientUpdate" onchange="patientUpdate();">
                                <option value="">--CHOOSE PATIENT--</option>
                                @foreach($patients as $patient)
                                    <option value="{{$patient->patient_id}}" >{{$patient->pid}} - {{$patient->name}}</option>
                                @endforeach
                            </select>
                            </div>
                            <div class="col-md-8">

                                @foreach($patients as $patient)
                                    <div id="patientdisplay{{$patient->patient_id}}" class="patientdisplay" style="display: none;">
                                        Patient ID: {{$patient->pid}} <br/>
                                        Patient Name: {{$patient->name}}<br/>
                                        Patient Age: {{$patient->age}}<br/>
                                        Patient Gender: @if($patient->gender==1) Male @else Femaile @endif<br/>
                                        Patient Mobile: {{$patient->telephone}}<br/>
                                    </div>
                                @endforeach


                            </div>

                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>


                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Doctor Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-4">
                                <select name="doctorId" id="doctorId" class="form-control doctorUpdate" onchange="doctorUpdate();">
                                    <option value="">--CHOOSE DOCTOR--</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{$doctor->doctorId}}">{{$doctor->doctorUniqueId}} - {{$doctor->doctorName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">

                                @foreach($doctors as $doctor)
                                    <div id="doctordisplay{{$doctor->doctorId}}" class="doctordisplay" style="display: none;">

                                        Doctor ID: {{$doctor->doctorUniqueId}}
                                        <br/>
                                        Doctor Name: {{$doctor->doctorName}}

                                    </div>
                                @endforeach

                            </div>

                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>

                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Fees Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12">
                                <div class="form-group col-md-12">
                                    Received Rs: <input type="text" name="fees" value="" class="form-control" style="display: inline;width: 200px;" /> with thanks towards doctor consultation charges
                                </div>
                            </div>

                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                        <div class="box-body">

                            <div class="col-md-12">
                                <div class="form-group col-md-12">
                                    <input type="hidden" name="hospitalId" value="{{Auth::user()->id}}" class="form-control"/>
                                    <input type="submit" name="addfee" value="SUBMIT FEE" class="form-controlX btn"/>
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


<script>
    function printDiv()
    {

        var divToPrint=document.getElementById('PagePrint');

        var newWin=window.open('','Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();

        setTimeout(function(){newWin.close();},10);

    }

    function patientUpdate()
    {
        var patientIdValue=document.getElementById('patientId');
        var pVal=patientIdValue.value;

        var elements=document.getElementsByClassName("patientdisplay");
        var n = elements.length;
        for (var i = 0; i < n; i++) {
            var e = elements[i];

            if(e.style.display == 'block') {
                e.style.display = 'none';
            } else {
                //e.style.display = 'block';
            }
        }
        document.getElementById('patientdisplay'+pVal).style.display="block";

    }


    function doctorUpdate()
    {
        var doctorIdValue=document.getElementById('doctorId');
        var dVal=doctorIdValue.value;
        var elements=document.getElementsByClassName("doctordisplay");
        var n = elements.length;
        for (var i = 0; i < n; i++) {
            var e = elements[i];

            if(e.style.display == 'block') {
                e.style.display = 'none';
            } else {
                //e.style.display = 'block';
            }
        }
        document.getElementById('doctordisplay'+dVal).style.display="block";

    }
</script>
@endsection
