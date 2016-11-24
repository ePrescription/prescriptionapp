<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{ URL::to('/') }}/images/prescription-160x160.jpg" class="img-circle" alt="User Image" />
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
    <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="@if($dashboard_menu==1) active @endif treeview">
            <a href="{{URL::to('/')}}/fronthospital/{{Auth::user()->id}}/dashboard">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="@if($patient_menu==1) active @endif treeview">
            <a href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/patients">
                <i class="fa fa-users"></i> <span>Patients</span>
            </a>
        </li>
        <li class="@if($profile_menu==1) active @endif treeview">
            <a href="#myaccount.html">
                <i class="fa fa-book"></i> <span>My Account</span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/profile"><i class="fa fa-circle-o"></i> View Profile</a></li>
                <li><a href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/editprofile"><i class="fa fa-circle-o"></i> Edit Profile</a></li>
                <!--
                <li><a href="{{URL::to('/')}}/pharmacy/rest/api/{{Auth::user()->id}}/changepassword"><i class="fa fa-circle-o"></i> Change Password</a></li>
                -->
            </ul>
        </li>
        <!--
          <li class="treeview">
            <a href="myaccount.html">
                <i class="fa fa-book"></i> <span>My Account</span>
            </a>
        </li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-laptop"></i>
              <span>UI Elements</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
              <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
              <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
              <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
              <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
              <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
            </ul>
          </li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-edit"></i> <span>Forms</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
              <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
              <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
            </ul>
          </li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-table"></i> <span>Tables</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
              <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
            </ul>
          </li>




          <li class="header">LABELS</li>
          <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
          <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
          <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li> -->
    </ul>
</section>