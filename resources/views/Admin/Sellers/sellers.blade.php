@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Sellers list')
<script src="{{URL::asset('assets/js/plugins/jquery/dist/jquery.min.js')}}"></script>
@section('content')


<!-- Main content -->
<div class="main-content">
  @include('Admin.Layouts.top_navbar')
 <!-- Header -->
  <div class="header bg-gradient-primary pb-1 pt-5 pt-md-8">
    <div class="container-fluid">
      <div class="header-body">
        <!-- Card stats -->
        <div class="row">
        </div>
      </div>
    </div>
  </div>
  <!-- Page content -->
  <div class="container-fluid mt--5">
    <div class="row">
        <div class="col-xl-12 mb-5 mb-xl-0">
            <div class="card shadow">
              <div class="card-header bg-transparent">
                <div class="row">

            <div class="col-md-8">
               <h5 class="heading-small text-muted mb-4">Sellers List</h5>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{url('/admin/addSellers')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Sellers</a>
            </div>
             
            <div class="col-lg-12">
                @include('flash-message')
               <div class="table-responsive">
                 <table class="table align-items-center text-center" id="activityTable">
                   <thead class="thead-light">
                     <tr>
                        <th scope="col" style="width: 10px;">Sr.No</th>
                        <th scope="col" class="text-center">Name</th>
                        <th scope="col" class="text-center">Email</th>
                        <th scope="col" class="text-center">Phone Number</th>
                        <th scope="col" class="text-center">Aadhar Number</th>
                        <th scope="col" class="text-center">Aadhar Front Image</th>
                        <th scope="col" class="text-center">Aadhar Back Image</th>
                        <th scope="col" class="text-center">Pan Number</th>
                        <th scope="col" class="text-center">PAN Card</th>
                        <th scope="col" class="text-center">GST Number</th>
                        <th scope="col" class="text-center">Cheque Number</th>
                        <th scope="col" class="text-center">Canceled Cheque</th>
                        <th scope="col" class="text-center">Account Verified</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                   </thead>
                   <tbody>
                      @if(count($sellers)>0)
                        @php
                            $i = ($sellers->currentpage()-1)* $sellers->perpage() + 1;
                        @endphp
                       @foreach($sellers as $seller)
                       <tr>
                            <td class="text-center" style="max-width: 10px;">
                               {{$i++}}
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$seller->name}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$seller->email}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$seller->phone_number}}</span>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$seller->aadhaar_number}}</span>
                                </div>
                            </td>

                            <td>
                                <a class="flex-column align-items-start py-1">
                                  <div class="d-flex">
                                      <div class="content-text ml-3">
                                          <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$seller->aadhaar_front_image}}">
                                              <img src="{{$seller->aadhaar_front_image}}" width="100" alt="" />
                                          </a>
                                      </div>
                                  </div>
                                </a>
                            </td>

                            <td>
                                <a class="flex-column align-items-start py-1">
                                  <div class="d-flex">
                                      <div class="content-text ml-3">
                                          <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$seller->aadhaar_back_image}}">
                                              <img src="{{$seller->aadhaar_back_image}}" width="100" alt="" />
                                          </a>
                                      </div>
                                  </div>
                                </a>
                            </td>
                             <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$seller->pan_number}}</span>
                                </div>
                            </td>
                            <td>
                                <a class="flex-column align-items-start py-1">
                                  <div class="d-flex">
                                      <div class="content-text ml-3">
                                          <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$seller->pan_image}}">
                                              <img src="{{$seller->pan_image}}" width="100" alt="" />
                                          </a>
                                      </div>
                                  </div>
                                </a>
                            </td>

                            <td>
                                <a class="flex-column align-items-start py-1">
                                  <div class="d-flex">
                                      <div class="content-text ml-3">
                                          <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$seller->gst_image}}">
                                              <img src="{{$seller->gst_image}}" width="100" alt="" />
                                          </a>
                                      </div>
                                  </div>
                                </a>
                            </td>
                             <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$seller->cheque_number}}</span>
                                </div>
                            </td>
                            <td>
                                <a class="flex-column align-items-start py-1">
                                  <div class="d-flex">
                                      <div class="content-text ml-3">
                                          <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$seller->cheque_image}}">
                                              <img src="{{$seller->cheque_image}}" width="100" alt="" />
                                          </a>
                                      </div>
                                  </div>
                                </a>
                            </td>

                            <td class="text-center">
                              @if($user->user_role == '6')
                                <button class="btn btn-icon btn-2 
                                    @if($seller->is_verified == 1) 
                                        btn-success 
                                    @else 
                                        btn-danger 
                                    @endif 
                                    btn-sm 
                                     verifiedSeller"
                                    data-toggle="tooltip" data-placement="top" title="
                                    @if($seller->is_verified == 1) 
                                        Verified 
                                    @else 
                                        Not Verified 
                                    @endif" 
                                    data-status="@if($seller->is_verified == 1) 0 @else 1 @endif"
                                    data-id="{{$seller->id}}">
                                        <span class="btn-inner--icon"><i class=" ni @if($seller->is_verified != 1) ni-fat-remove @else ni-check-bold @endif"></i></span>
                                </button>
                              @else
                                  <span class="badge badge-dot mr-4">
                                  @if($seller->is_verified == 1)
                                  <span class="d-none not_ap_ms"><i class="bg-danger"></i> Not verified</span>
                                  <span class="approved_ms"><i class="bg-success"></i> Verified</span>
                                  @else
                                  <span class="not_ap_ms"><i class="bg-danger"></i> Not verified</span>
                                  <span class="d-none approved_ms"><i class="bg-success"></i> Verified</span>
                                  @endif
                                  </span>
                              @endif
                            </td>
                            
                            <td class="text-center">
                                <span class="badge badge-dot mr-4">
                                   @if($seller->is_active == 1)
                                    <span class="d-none not_ap_ms"><i class="bg-danger"></i> Deactive</span>
                                   <span class="approved_ms"><i class="bg-success"></i> Active</span>
                                   @else
                                   <span class="not_ap_ms"><i class="bg-danger"></i> Deactive</span>
                                   <span class="d-none approved_ms"><i class="bg-success"></i> Active</span>
                                   @endif
                                 </span>
                            </td>
                          
                          <td class="text-right">

                            <!--button class="btn btn-icon btn-2 
                                @if($seller->is_active == 1) 
                                    btn-success 
                                @else 
                                    btn-danger 
                                @endif 
                                btn-sm activeInactiveSeller" data-toggle="tooltip" data-placement="top" title="
                                @if($seller->is_active == 1) 
                                    Active 
                                @else 
                                    In-active 
                                @endif" 
                                data-status="@if($seller->is_active == 1) 0 @else 1 @endif"
                                data-id="{{$seller->id}}">
                                    <span class="btn-inner--icon"><i class=" ni @if($seller->is_active != 1) ni-fat-remove @else ni-check-bold @endif"></i></span>
                              </button-->
                              @if(in_array($user->user_role,['6','1','0']))
                                  @if($seller->is_active == 1)
                                      
                                    <button  class="btn btn-icon btn-2 btn-danger btn-sm activeInactiveSeller inactive_cls" type="button" data-id="{{$seller->id}}" data-status='0' data-toggle="tooltip" data-placement="top" title="Deactivate">
                                      <span class="btn-inner--icon">Deactivate</span>
                                    </button>

                                    <button class="btn btn-icon btn-2 btn-success btn-sm activeInactiveSeller  d-none active_cls" type="button" data-id="{{$seller->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Activate">
                                      <span class="btn-inner--icon">Activate</span>
                                    </button>

                                  @else

                                    <button class="btn btn-icon btn-2 btn-success btn-sm activeInactiveSeller inactive_cls" type="button" data-id="{{$seller->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Activate"><span class="btn-inner--icon">Activate</span>
                                    </button>

                                    <button class="btn btn-icon btn-2 btn-danger btn-sm  activeInactiveSeller  d-none active_cls" type="button" data-id="{{$seller->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Deactivate"><span class="btn-inner--icon">Deactivate</span>
                                    </button>

                                  @endif
                              @endif

                            <a class="btn btn-icon btn-2 btn-info btn-sm" href="{{url('admin/sellerInvoices')}}/{{base64_encode($seller->id)}}" data-toggle="tooltip" data-placement="top" title="Transactions">
                              <span class="btn-inner--icon"><i class="fas fa-file-invoice"></i></span>
                            </a>

                            @if( ( $user->id == $seller->added_by && $user->user_role == $seller->added_by_role) || in_array($user->user_role,['6','1','0']))
                             <a class="btn btn-icon btn-2 btn-info btn-sm" href="{{url('admin/editSeller')}}/{{base64_encode($seller->id)}}" data-toggle="tooltip" data-placement="top" title="Edit">
                               <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                             </a>
                            @else
                              <a class="btn btn-icon btn-2 btn-info btn-sm" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Edit not allowed">
                                <span class="btn-inner--icon"><i class="fas fa-ban"></i></span>
                              </a>
                            @endif

                             <a class="btn btn-icon btn-2 btn-info btn-sm" href="{{url('admin/seller/export')}}/{{$seller->id}}" data-toggle="tooltip" data-placement="top" title="Export">
                               <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                             </a>
                          </td>
                           
                       </tr>
                       @endforeach
                     @else
                       <tr>
                         <th colspan="12">
                           <div class="media-body text-center">
                               <span class="mb-0 text-sm">No Seller found.</span>
                           </div>
                         </th>
                       </tr>
                     @endif
                   </tbody>
                 </table>
               </div>
               <div class="ads_pagination mt-3 mb-0">
                   {{$sellers->appends(request()->except('page'))->links()}}
               </div>
            </div>
                </div>
            </div>
    </div>
  
    <!-- Footer Section Include -->
        @include('Admin.Layouts.footer')
    <!-- End Footer Section Include -->
  </div>
</div>
@endsection