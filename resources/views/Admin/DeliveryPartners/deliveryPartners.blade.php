@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Delivery Partners list')
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
               <h5 class="heading-small text-muted mb-4">Delivery Partners List</h5>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{url('/admin/addDeliveryPartners')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Delivery partners</a>
            </div>
             
            <div class="col-lg-12">
                @include('flash-message')
               <div class="table-responsive">
                 <table class="table align-items-center text-center" id="activityTable">
                   <thead class="thead-light">
                     <tr>
                        <th scope="col" style="width: 10px;">Sr.No</th>
                        <th scope="col" style="width: 10px;">Image</th>
                        <th scope="col" class="text-center">Company Name</th>
                        <th scope="col" class="text-center">Contact Person Name</th>
                        <th scope="col" class="text-center">Contact Email</th>
                        <th scope="col" class="text-center">Contact Phone Number</th>
                        <th scope="col" class="text-center">Address</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                   </thead>
                   <tbody>
                      @if(count($deliverPartners)>0)
                        @php
                            $i = ($deliverPartners->currentpage()-1)* $deliverPartners->perpage() + 1;
                        @endphp
                       @foreach($deliverPartners as $deliverPartner)
                       <tr>
                            <td class="text-center" style="max-width: 10px;">
                               {{$i++}}
                            </td>

                            <td>
                              <a class="flex-column align-items-start py-1">
                                <div class="d-flex">
                                    <div class="content-text ml-3">
                                        <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$deliverPartner->image}}">
                                            <img src="{{$deliverPartner->image}}" width="100" alt="" />
                                        </a>
                                    </div>
                                </div>
                              </a>
                            </td>

                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$deliverPartner->company_name}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$deliverPartner->name}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$deliverPartner->email}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$deliverPartner->phone_number}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$deliverPartner->address}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-dot mr-4">
                                   @if($deliverPartner->is_active == 1)
                                    <span class="d-none not_ap_ms"><i class="bg-danger"></i> In-active</span>
                                   <span class="approved_ms"><i class="bg-success"></i> Active</span>
                                   @else
                                   <span class="not_ap_ms"><i class="bg-danger"></i> In-active</span>
                                   <span class="d-none approved_ms"><i class="bg-success"></i> Active</span>
                                   @endif
                                 </span>
                            </td>
                            <td class="text-right">

                              @if( in_array( $user->user_role,['0','1','6']) )
                                @if($deliverPartner->is_active == 1)
                                  
                                  <button  class="btn btn-icon btn-2 btn-danger btn-sm 
                                    @php  if(in_array( $user->user_role,['1','6','0'])) echo 'activeInactiveDeliveryPartner'; @endphp
                                    inactive_cls" type="button" data-id="{{$deliverPartner->id}}" data-status='0' data-toggle="tooltip" data-placement="top" title="Inactive">
                                    <span class="btn-inner--icon">Inactive</span>
                                  </button>

                                  <button class="btn btn-icon btn-2 btn-success btn-sm 
                                   @php  if(in_array( $user->user_role,['1','6','0']))  echo 'activeInactiveDeliveryPartner'; @endphp
                                    d-none active_cls" type="button" data-id="{{$deliverPartner->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Active">
                                    <span class="btn-inner--icon">Active</span>
                                  </button>

                                @else

                                  <button class="btn btn-icon btn-2 btn-success btn-sm 
                                      @php  if(in_array( $user->user_role,['1','6','0']))  echo 'activeInactiveDeliveryPartner' @endphp
                                      inactive_cls" type="button" data-id="{{$deliverPartner->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Active"><span class="btn-inner--icon">Active</span>
                                  </button>

                                  <button class="btn btn-icon btn-2 btn-danger btn-sm 
                                      @php  if(in_array( $user->user_role,['1','6','0'])) echo 'activeInactiveDeliveryPartner'; @endphp
                                      d-none active_cls" type="button" data-id="{{$deliverPartner->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Inactive"><span class="btn-inner--icon">Inactive</span>
                                  </button>

                                @endif
                              @endif
                              
                              <!--button class="btn btn-icon btn-2 
                                  @if($deliverPartner->is_active == 1) 
                                      btn-success 
                                  @else 
                                      btn-danger 
                                  @endif 
                                  btn-sm activeInactiveDeliveryPartner" data-toggle="tooltip" data-placement="top" title="
                                  @if($deliverPartner->is_active == 1) 
                                      Active 
                                  @else 
                                      In-active 
                                  @endif" 
                                  data-status="@if($deliverPartner->is_active == 1) 0 @else 1 @endif"
                                  data-id="{{$deliverPartner->id}}">
                                      <span class="btn-inner--icon"><i class=" ni @if($deliverPartner->is_active != 1) ni-fat-remove @else ni-check-bold @endif"></i></span>
                                </button-->

                                @if( ( $user->id == $deliverPartner->added_by && $user->user_role == $deliverPartner->added_by_role) || in_array($user->user_role,['0','1','6']))

                                <a class="btn btn-icon btn-2 btn-info btn-sm" href="{{url('admin/editDeliveryPartners')}}/{{base64_encode($deliverPartner->id)}}" data-toggle="tooltip" data-placement="top" title="Edit">
                                  <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                </a>
                                @else
                                 <a class="btn btn-icon btn-2 btn-info btn-sm" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Edit not allowed">
                                  <span class="btn-inner--icon"><i class="fas fa-ban"></i></span>
                                </a>
                                @endif

                             <!--button class="btn btn-icon btn-2 btn-danger btn-sm delete_delivery_partner" type="button" data-id="{{$deliverPartner->id}}" data-toggle="tooltip" data-placement="top" title="Delete">
                               <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                             </button-->
                          </td>
                           
                       </tr>
                       @endforeach
                     @else
                       <tr>
                         <th colspan="12">
                           <div class="media-body text-center">
                               <span class="mb-0 text-sm">No User found.</span>
                           </div>
                         </th>
                       </tr>
                     @endif
                   </tbody>
                 </table>
               </div>
               <div class="ads_pagination mt-3 mb-0">
                   {{$deliverPartners->appends(request()->except('page'))->links()}}
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