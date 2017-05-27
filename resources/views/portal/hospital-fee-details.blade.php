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

        <div style="width:98%;float: right;padding:10px;margin:10px;">

            <a href="" style="float: right;" ><button type="button" class="btn btn-success btn-xs" onclick="javascript:printDiv();"><i class="fa fa-print"></i> PRINT</button></a>

            <a href="{{URL::to('/')}}/fronthospital/receipt/{{$receiptId}}/sms/{{$feeReceiptDetails['patientDetails']->telephone}}" style="float: right;padding-right:10px;" ><button type="button" class="btn btn-success btn-xs" ><i class="fa fa-mobile"></i> SMS</button></a>

            <a href="{{URL::to('/')}}/fronthospital/receipt/{{$receiptId}}/mail/{{$feeReceiptDetails['patientDetails']->email}}" style="float: right;padding-right:10px;" ><button type="button" class="btn btn-success btn-xs" ><i class="fa fa-envelope-o"></i> E-MAIL</button></a>

        </div>

        <!-- Main content -->
        <section id="PagePrintX" class="content">
            <div class="row">


                @if (session()->has('message'))
                    <div class="col-xs-12 login-title">
                                <span style="color:red;">
                                    <b>{{session('message')}}</b>
                                </span>
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="col-xs-12 login-title">
                                <span style="color:green;">
                                    <b>{{session('success')}}</b>
                                </span>
                    </div>
                @endif

                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Hospital Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="col-md-12">
                                <?php if(!empty($feeReceiptDetails['hospitalDetails']->hospital_logo)) { ?>
                                <div class="form-group col-md-3">
                                        {{$feeReceiptDetails['hospitalDetails']->hospital_logo}}
                                </div>
                                <div class="form-group col-md-9">
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['hospitalDetails']->hospital_name}}
                                         ( {{$feeReceiptDetails['hospitalDetails']->hid}} )
                                    </div>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['hospitalDetails']->address}}, {{$feeReceiptDetails['hospitalDetails']->cityName}}, {{$feeReceiptDetails['hospitalDetails']->country}}
                                    </div>
                                </div>
                                <?php } else { ?>
                                    <div class="form-group col-md-12">
                                        <div class="col-sm-6">
                                            {{$feeReceiptDetails['hospitalDetails']->hospital_name}}
                                            ( {{$feeReceiptDetails['hospitalDetails']->hid}} )
                                        </div>
                                        <div class="col-sm-6">
                                            {{$feeReceiptDetails['hospitalDetails']->address}}, {{$feeReceiptDetails['hospitalDetails']->cityName}}, {{$feeReceiptDetails['hospitalDetails']->country}}
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>

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
                                        {{$feeReceiptDetails['patientDetails']->pid}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Name</label>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['patientDetails']->name}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Phone Number</label>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['patientDetails']->telephone}}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Address</label>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['patientDetails']->address}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Relationship</label>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['patientDetails']->relationship}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Patient Relation Name</label>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['patientDetails']->spouseName}}
                                    </div>
                                </div>
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

                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Doctor ID</label>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['doctorDetails']->did}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Doctor Name</label>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['doctorDetails']->name}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Doctor Designation</label>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['doctorDetails']->designation}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-sm-6 control-label">Doctor Department</label>
                                    <div class="col-sm-6">
                                        {{$feeReceiptDetails['doctorDetails']->department}}
                                    </div>
                                </div>
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
                                    Received Rs: {{$feeReceiptDetails['feeDetails']['fee']}} ( In Words {{$feeReceiptDetails['feeDetails']['inWords']}} ) with thanks towards doctor consultation charges
                                </div>
                            </div>
                            <div class="col-md-1"></div>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>

            </div><!-- /.row -->
        </section><!-- /.content -->



        <!-- PRINT OPEN -->
        <section id="PagePrint" class="content hidden" style="font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
            <div class="row">

                <div class="col-xs-12" style="width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">
                    <div class="box">
                        <div class="box-body">
                            <div class="col-md-12" style="width:100%;float:left;">
                                <div class="form-group col-md-3" style="width:50%;float:left;">
                                    {{$feeReceiptDetails['hospitalDetails']->hospital_logo}}-
                                </div>
                                <div class="form-group col-md-9" style="width:50%;float:left;">
                                    <div class="col-sm-12" style="width:100%;float:left;">
                                        {{$feeReceiptDetails['hospitalDetails']->hospital_name}}
                                        ( {{$feeReceiptDetails['hospitalDetails']->hid}} )
                                    </div>
                                    <div class="col-sm-12" style="width:100%;float:left;">
                                        {{$feeReceiptDetails['hospitalDetails']->address}}, {{$feeReceiptDetails['hospitalDetails']->cityName}}, {{$feeReceiptDetails['hospitalDetails']->country}}
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>

                <div class="col-xs-12" style="width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Patient Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="col-md-12" style="width:100%;float:left;">
                                <div class="form-group col-md-12" style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Patient ID</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['patientDetails']->pid}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Patient Name</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['patientDetails']->name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Phone Number</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['patientDetails']->telephone}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Patient Address</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['patientDetails']->address}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Patient Relationship</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['patientDetails']->relationship}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Patient Relation Name</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['patientDetails']->spouseName}}
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>


                <div class="col-xs-12" style="width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Doctor Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12" style="width:100%;float:left;">
                                <div class="form-group col-md-12" style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Doctor ID</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['doctorDetails']->did}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12"  style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Doctor Name</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['doctorDetails']->name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Doctor Designation</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['doctorDetails']->designation}}
                                    </div>
                                </div>
                                <div class="form-group col-md-12" style="width:50%;float:left;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Doctor Department</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$feeReceiptDetails['doctorDetails']->department}}
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>

                <div class="col-xs-12" style="width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Fees Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12" style="width:100%;float:left;">
                                <div class="form-group col-md-12" style="width:100%;float:left;">
                                    Received Rs: {{$feeReceiptDetails['feeDetails']['fee']}} ( In Words {{$feeReceiptDetails['feeDetails']['inWords']}} ) with thanks towards doctor consultation charges
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-12" style="width:100%;float:left;">
                                <div class="form-group col-md-12" style="width:100%;float:left;">
                                    <br/><br/>
                                    <p style="text-align: right;"> Authorized Signatory</p>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>

            </div><!-- /.row -->
        </section>
<?php /* ?>
        <section id="PagePrint" class="content hidden" style="font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
            <div class="row">

                <div class="col-xs-12">
                    <div style="width:100%;float:left;">
                        <h2 style="padding: 0px;margin: 8px;color: #FFF; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-weight: bold;    font-size: 18px;">
                            Dr All Caps - Medical Prescription<br/>
                            {{Session::get('AuthDisplayName')}} - {{Session::get('LoginDoctorDetails')}}
                            <br/>
                            {{Session::get('LoginHospitalDetails')}}

                        </h2>
                    </div>
                </div>

                <div class="col-xs-6"  style="width:50%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Patient Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12" style="width:100%;float:left;">
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Patient ID</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['PatientProfile'][0]->pid}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Patient Name</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['PatientProfile'][0]->name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Phone Number</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['PatientProfile'][0]->telephone}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">E-Mail</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['PatientProfile'][0]->email}}
                                    </div>
                                </div>




                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>
                <div class="col-xs-6"  style="width:50%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Doctor Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12" style="width:100%;float:left;">
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Doctor ID</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['DoctorProfile'][0]->did}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Doctor Name</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['DoctorProfile'][0]->name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">Phone Number</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['DoctorProfile'][0]->telephone}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6" style="width:100%;float:left;line-height:20px;">
                                    <label class="col-sm-6 control-label" style="width:50%;float:left;">E-Mail</label>
                                    <div class="col-sm-6" style="width:50%;float:left;">
                                        {{$prescriptionDetails['DoctorProfile'][0]->email}}
                                    </div>
                                </div>




                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>
                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header" style="width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 18px;">
                            <h3 class="box-title" style="line-height:30px;width:500px;float:left;">Drug Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">
                            <table id="example2" class="table table-bordered table-hover" border="0" style="width:100%;border:1px solid #000;padding:5px;margin:5px;">
                                <thead>
                                <tr style="width:100%;line-height:30px;">
                                    <th  style="width:20%;    text-align: left;line-height:30px;">TRADE NAME</th>
                                    <th  style="width:20%;    text-align: left;line-height:30px;">FORMULATIONS</th>
                                    <th  style="width:15%;    text-align: left;line-height:30px;">TYPE</th>
                                    <th  style="width:10%;    text-align: left;line-height:30px;">DOSAGE</th>
                                    <th  style="width:10%;    text-align: left;line-height:30px;">DAYS</th>
                                    <th  style="width:25%;    text-align: left;line-height:30px;">PERIODICITY / INTERVALS</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($prescriptionDetails['PatientDrugDetails'] as $prescription)
                                    <tr style="width:100%;line-height:30px;">
                                        <td>{{$prescription->trade_name}}</td>
                                        <td>{{$prescription->formulation_name}}</td>
                                        <td>{{$prescription->intake_form}}</td>
                                        <td>{{$prescription->dosage}}</td>
                                        <td>{{$prescription->no_of_days}}</td>
                                        <td>
                                            {{$prescription->morning}} - {{$prescription->afternoon}} - {{$prescription->night}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div><!-- /.col -->

                <div class="col-xs-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title" style="line-height:30px;width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 18px;">Notes</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="col-md-12">
                                <div class="form-group col-md-12">
                                    <div class="col-sm-12" style="line-height:30px;width:100%;float:left;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 16px;">
                                        @foreach($prescriptionDetails['PrescriptionInfo'] as $prescriptioninfo)
                                            {{$prescriptioninfo->notes}}
                                        @endforeach
                                    </div>
                                </div>



                            </div>
                            <div class="col-md-1"></div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
        <!-- PRINT END -->
<?php */ ?>

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
</script>
@endsection
