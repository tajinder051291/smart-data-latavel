
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row align-items-center">
            <div class="col-8 collapse-brand">
              <a href="{{url('/admin/dashboard')}}">
                <img src="{{URL::asset('assets/img/pic.png')}}"  alt="...">
              </a>

            </div>
            <div class="col-4 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->
        <ul class="navbar-nav sidebar_nav">
          <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{url('/admin/dashboard')}}">
              <i class="ni ni-tv-2 text-primary"></i> Dashboard
            </a>
          </li>
          <!--li class="nav-item">
            <a class="nav-link" href="{{url('/admin/app-permissions')}}">
              <i class="fa fa-universal-access text-blue" aria-hidden="true"></i>
              <span class="d-md-inline-block">App Permissions</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('/admin/role-management')}}">
              <i class="fa fa-user-tag text-yellow" aria-hidden="true"></i>
              <span class="d-md-inline-block">User Role Management</span>
            </a>
          </li-->
          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && in_array( Auth::guard('manager')->user()['user_role'],['1']) ) )
          <li class="nav-item">
            <a class="nav-link" href="{{url('/admin/users')}}">
              <i class="fa fa-users text-success" aria-hidden="true"></i>
              <span class="d-md-inline-block">Users</span>
            </a>
          </li>
          @endif

          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && in_array( Auth::guard('manager')->user()['user_role'],['1']) ) )
          
          <li class="nav-item">
            <a class="nav-link" href="{{url('/admin/mobile-brands')}}">
              <i class="fa fa-mobile text-red" aria-hidden="true"></i>
              <span class="d-md-inline-block">Mobile Brands</span>
            </a>
          </li>

          @endif

          <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/sellers') ? 'active' : '' }}" href="#navbar-dashboards" data-toggle="collapse" role="button" aria-expanded="{{ request()->is('admin/sellers') ? 'true' : 'false' }}" aria-controls="navbar-dashboards">
              <i class="fa fa-store text-info"></i>
              <span class="nav-link-text">Sellers</span>
            </a>
            <div class="collapse {{ request()->is('admin/sellers/1') ? 'show' : '' }} {{ request()->is('admin/sellers/2') ? 'show' : '' }} {{ request()->is('admin/sellers/3') ? 'show' : '' }}" id="navbar-dashboards" style="">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item {{ request()->is('admin/sellers/1') ? 'active' : '' }}">
                  <a href="{{url('/admin/sellers/1')}}" class="nav-link">
                    <span class="sidenav-mini-icon"> <i class="fa fa-store text-info"></i> </span>&nbsp;
                    <span class="sidenav-normal"> All </span>
                  </a>
                </li>
                <li class="nav-item {{ request()->is('admin/sellers/2') ? 'active' : '' }}">
                  <a href="{{url('/admin/sellers/2')}}" class="nav-link">
                    <span class="sidenav-mini-icon"> <i class="fa fa-store text-green"></i> </span>&nbsp;
                    <span class="sidenav-normal">  Approved </span>
                  </a>
                </li>
                <li class="nav-item {{ request()->is('admin/sellers/3') ? 'active' : '' }}">
                  <a href="{{url('/admin/sellers/3')}}" class="nav-link">
                    <span class="sidenav-mini-icon"> <i class="fa fa-store text-red"></i> </span>&nbsp;
                    <span class="sidenav-normal">  Pending </span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('/admin/delivery-partners')}}">
              <i class="fa fa-truck text-error" aria-hidden="true"></i>
              <span class="d-md-inline-block">Delivery Partners</span>
            </a>
          </li>
          
          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && in_array( Auth::guard('manager')->user()['user_role'],['1','6']) ) )
          <li class="nav-item">
            <a class="nav-link" href="{{url('/admin/invoices')}}">
              <i class="fa fa-file-invoice text-warning" aria-hidden="true"></i>
              <span class="d-md-inline-block">Invoices</span>
            </a>
          </li>
          @endif

          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && in_array( Auth::guard('manager')->user()['user_role'],['1']) ) )
          <li class="nav-item">
            <a class="nav-link" href="{{url('/admin/faq')}}">
              <i class="fa fa-question-circle text-success" aria-hidden="true"></i>
              <span class="d-md-inline-block">FAQs</span>
            </a>
          </li>
          @endif
          
          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && in_array( Auth::guard('manager')->user()['user_role'],['1']) ) )
          <li class="nav-item">
            <a class="nav-link" href="{{url('/admin/feedback/list')}}">
              <i class="fa fa-comments text-success" aria-hidden="true"></i>
              <span class="d-md-inline-block">Feedbacks</span>
            </a>
          </li>
          @endif
{{-- 
          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && in_array( Auth::guard('manager')->user()['user_role'],['1']) ) )
          <li class="nav-item">
            <a class="nav-link" href="{{url('/admin/query/list')}}">
              <i class="fa  fa-ticket-alt text-warning" aria-hidden="true"></i>
              <span class="d-md-inline-block">Queries</span>
            </a>
          </li>
          @endif --}}

          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && in_array( Auth::guard('manager')->user()['user_role'],['1']) ) )
           <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/query/list') ? 'active' : '' }}" href="#navbar-queries" data-toggle="collapse" role="button" aria-expanded="{{ request()->is('/admin/query/list') ? 'true' : 'false' }}" aria-controls="navbar-queries">
              <i class="fa fa-ticket-alt text-success"></i>
              <span class="nav-link-text">Queries</span>
            </a>
            <div class="collapse {{ request()->is('admin/query/list/2') ? 'show' : '' }} {{ request()->is('admin/query/list/0') ? 'show' : '' }} {{ request()->is('admin/query/list/1') ? 'show' : '' }}" id="navbar-queries" style="">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item {{ request()->is('admin/query/list/2') ? 'active' : '' }}">
                  <a href="{{url('admin/query/list/2')}}" class="nav-link">
                    <span class="sidenav-mini-icon"> <i class="fa fa-ticket-alt text-info"></i> </span>&nbsp;
                    <span class="sidenav-normal"> All </span>
                  </a>
                </li>
                <li class="nav-item {{ request()->is('admin/query/list/1') ? 'active' : '' }}">
                  <a href="{{url('admin/query/list/1')}}" class="nav-link">
                    <span class="sidenav-mini-icon"> <i class="fa fa-ticket-alt text-green"></i> </span>&nbsp;
                    <span class="sidenav-normal">  Open </span>
                  </a>
                </li>
                <li class="nav-item {{ request()->is('admin/query/list/0') ? 'active' : '' }}">
                  <a href="{{url('admin/query/list/0')}}" class="nav-link">
                    <span class="sidenav-mini-icon"> <i class="fa fa-ticket-alt text-red"></i> </span>&nbsp;
                    <span class="sidenav-normal">  Closed </span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          @endif


        </ul>
      </div>