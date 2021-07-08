@extends('Admin.Layouts.mainlayout')
@section('title', 'Dashboard')
@section('content')

<!-- Main content -->
<div class="main-content">
  @include('Admin.Layouts.top_navbar')
  <!-- Header -->
  <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
      <div class="header-body">
        <!-- Card stats -->
        
        <div class="row">
          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && Auth::guard('manager')->user()['user_role']=='1' ) )
          <div class="col-xl-3 col-lg-6 mb-2">
            <a href="{{url('/admin/users')}}"><div class="card card-stats mb-4 mb-xl-0">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Users</h5>
                    <span class="h2 font-weight-bold mb-0">{{$data['user']}}</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                      <i class="fa fa-users"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div></a>
          </div>
          @endif

          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && Auth::guard('manager')->user()['user_role']=='1' ) )

          <div class="col-xl-3 col-lg-6 mb-2">
            <a href="{{url('/admin/mobile-brands')}}"><div class="card card-stats mb-4 mb-xl-0">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Mobile Brands</h5>
                    <span class="h2 font-weight-bold mb-0">{{$data['total_mobile_brands']}}</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                      <i class="fab fa-apple"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div></a>
          </div>
          @endif

          @if(Auth::guard('admin')->check() || ( Auth::guard('manager')->check() && Auth::guard('manager')->user()['user_role']=='1' ) )

          <div class="col-xl-3 col-lg-6 mb-2">
            <a href="{{url('/admin/mobile-brands')}}"><div class="card card-stats mb-4 mb-xl-0">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Mobile Models</h5>
                    <span class="h2 font-weight-bold mb-0">{{$data['total_mobile_models']}}</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                      <i class="fa fa-mobile "></i>
                    </div>
                  </div>
                </div>
              </div>
            </div></a>
          </div>
          @endif

          <div class="col-xl-3 col-lg-6 mb-2">
            <a href="{{url('/admin/delivery-partners')}}"><div class="card card-stats mb-4 mb-xl-0">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Delivery Partners</h5>
                    <span class="h2 font-weight-bold mb-0">{{$data['total_delivery_partners']}}</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                      <i class="fa fa-truck"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div></a>
          </div>

          <div class="col-xl-3 col-lg-6 mb-2">
            <a href="{{url('/admin/sellers/1')}}"><div class="card card-stats mb-4 mb-xl-0">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Sellers</h5>
                    <span class="h2 font-weight-bold mb-0">{{$data['total_sellers']}}</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                      <i class="fa fa-store"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div></a>
          </div>

          @if(( ( Auth::guard('manager')->check() || Auth::guard('admin')->check()) && in_array(Auth::user()->user_role,['6','0','1'] ) ))
            <div class="col-xl-3 col-lg-6 mb-2">
              <a href="{{url('/admin/invoices')}}"><div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Invoices</h5>
                      <span class="h2 font-weight-bold mb-0">{{$data['total_invoices']}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                        <i class="fa fa-file-invoice"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div></a>
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>
  <!-- Page content -->
  <div class="container-fluid mt-7">
    <div class="row">
    </div>
    <div class="row mt-5">
    </div>
    <!-- Footer Section Include -->
        @include('Admin.Layouts.footer')
    <!-- End Footer Section Include -->
  </div>
</div>

@endsection