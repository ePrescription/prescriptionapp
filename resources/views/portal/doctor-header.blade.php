
<header class="main-header">
    <!-- Logo -->
    <a href="index.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{ URL::to('/') }}/images/head-logoicon.png" width="100%"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="{{ URL::to('/') }}/images/head-logo-new.png" width="100%"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div style="width:700px;float:left;">
            <h2 style="padding: 0px;margin: 8px; color: #FFF; font-weight: bold;    font-size: 18px;">
                ePrescription and Lab Tests Application <br/>
                {{Session::get('AuthDisplayName')}} - {{Session::get('LoginDoctorDetails')}}
                <br/>
                {{Session::get('LoginHospitalDetails')}}

            </h2>
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
