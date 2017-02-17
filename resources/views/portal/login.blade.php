@extends('layout.master-login')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
@stop

@section('content')

    <style>
        html, body {
            min-height: auto !important;
        }
    </style>
<div class="login-box">
    <div class="login-logo">
        <a href="index.html"><img src="images/head-logo-new.png"></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        @if (session()->has('message'))
            <div style="padding-bottom: 5px; margin-left: -14px;">
                                <span style="color:red; margin:4px; padding: 4px;">
                                    <b>{{session('message')}}</b>
                                </span>
            </div>
        @endif

        {!! Form::open( array( 'route' => array('user.dologin') ,'role' => 'form' ,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal nobottommargin') ) !!}

            <div class="form-group has-feedback">
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" />
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Remember Me
                        </label>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div><!-- /.col -->
            </div>
        </form>



        <a href="#" style="display:none;">I forgot my password</a><br>

        <strong><a href="#" style="font-size: 12px;">Powered by Daiwik Business Solutions Private Limited</a></strong>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
@endsection

@section('scripts')
@stop
