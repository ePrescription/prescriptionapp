<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{ URL::to('/') }}/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
        </div>
        <div class="pull-left info">
            <p>{{Session::get('AuthDisplayName')}}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>
    <!-- search form -->
    <form action="#" method="get" class="sidebar-form hidden">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search..." />
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
        </div>
    </form>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->

    @if(!empty(Session::get('LoginHospitalDetails')))
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li  class="@if($dashboard_menu==1) active @endif treeview">
                <a href="{{URL::to('/')}}/doctor/{{Auth::user()->id}}/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li  class="@if($patient_menu==1) active @endif treeview">
                <a href="{{URL::to('/')}}/doctor/rest/api/{{Auth::user()->id}}/hospital/{{Session::get('LoginUserHospital')}}/patients">
                    <i class="fa fa-users"></i> <span>Patients</span>
                </a>
            </li>
            <li  class="@if($prescription_menu==1) active @endif treeview">
                <a href="{{URL::to('/')}}/doctor/rest/api/{{Auth::user()->id}}/hospital/{{Session::get('LoginUserHospital')}}/prescriptions">
                    <i class="fa fa-pencil-square-o"></i> <span>Prescriptions</span>
                </a>
            </li>
            <li  class="@if($lab_menu==1) active @endif treeview">
                <a href="{{URL::to('/')}}/doctor/rest/api/{{Auth::user()->id}}/hospital/{{Session::get('LoginUserHospital')}}/labtests">
                    <i class="fa fa-flask"></i> <span>Lab Tests</span>
                </a>
            </li>

        </ul>
    @endif
</section>