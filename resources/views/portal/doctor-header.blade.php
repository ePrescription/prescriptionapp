
<header class="main-header">
    <!-- Logo -->
    <a href="index.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{ URL::to('/') }}/images/head-logoicon.png" width="100%"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="{{ URL::to('/') }}/images/head-logosmall.png" width="100%"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div style="width:700px;float:left;">
            <h2 style="padding: 0px;margin: 8px; color: #FFF; font-weight: bold;    font-size: 18px;">
                Dr All Caps<br/>
                {{Session::get('AuthDisplayName')}} - {{Session::get('LoginDoctorDetails')}}
                <br/>
                @if(!empty(Session::get('LoginHospitalDetails')))
                {{Session::get('LoginHospitalDetails')}}
                @endif
            </h2>
        </div>
        <div style="width:200px;float:left;">
<br/>
            @if(!empty(Session::get('LoginUserHospital')))
                <form id="changehospital" name="changehospital" action="{{URL::to('/')}}/doctor/changehospital" role="form" method="POST">
                    <?php $hospitalsArray = Session::get('LoginUserHospitals'); ?>
                    <select name="hospital" class="form-control" onchange="javascript:this.form.submit();">

                        @foreach($hospitalsArray as $hospitalValue)
                            <option value="{{$hospitalValue->id}}" @if(Session::get('LoginUserHospital')==$hospitalValue->id) selected @endif >{{$hospitalValue->hospital_name}}</option>
                        @endforeach
                    </select>
                </form>
            @else
            <form id="changehospital" name="changehospital" action="{{URL::to('/')}}/doctor/changehospital" role="form" method="POST">
                <?php $hospitalsArray = Session::get('LoginUserHospitals'); ?>
                <select name="hospital" class="form-control" onchange="javascript:this.form.submit();">
                    <option selected>---Choose Your Hospital---</option>
                    @foreach($hospitalsArray as $hospitalValue)
                    <option value="{{$hospitalValue->id}}" >{{$hospitalValue->hospital_name}}</option>
                    @endforeach
                </select>
            </form>
            @endif

        </div>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ URL::to('/logout') }}" data-toggle="control-sidebarX"><i class="fa fa-sign-out"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
