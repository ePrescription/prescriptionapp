<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <section id="PagePrint" class="content hiddenX" style="font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
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

        </div><!-- /.content-wrapper -->


    </div><!-- ./wrapper -->

</body>
</html>
