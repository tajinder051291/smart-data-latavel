@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | App Permissions list')
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
               <h5 class="heading-small text-muted mb-4">App Permisions List</h5>
            </div>
            <div id="adPermission" class="col-md-4 text-right">
                <a href="{{url('/admin/addAppPermissions')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Permissions</a>
            </div>
            <div id="showDelPermission" class="col-md-4 text-right">
              <button class="btn btn-primary btn-sm deletePermission" data-url="{{url('/admin/deleteSelectedPermissions')}}">Delete Selected Items</button>
            </div> 
            <div class="col-lg-12">
                @include('flash-message')
               <div class="table-responsive">
                 <table class="table align-items-center text-center" id="activityTable">
                   <thead class="thead-light">
                     <tr>
                        <th>
                          <div class="custom-control custom-checkbox mb-3">
                              <input class="custom-control-input" id="customCheck01" type="checkbox">
                              <label class="custom-control-label" for="customCheck01"></label>
                            </div>
                        </th>
                        <th scope="col" style="width: 10px;">Sr.No</th>
                        <th scope="col" class="text-center">Name</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                   </thead>
                   <tbody>
                      @if(count($appPermissions)>0)
                        @php
                            $i = ($appPermissions->currentpage()-1)* $appPermissions->perpage() + 1;
                        @endphp
                       @foreach($appPermissions as $appPermission)
                       <tr>
                            <td>
                              <div class="custom-control custom-checkbox mb-3">
                                <input class="custom-control-input sub_chk_with" id="customCheck{{$appPermission->id}}" type="checkbox" data-id="{{$appPermission->id}}">
                                <label class="custom-control-label" for="customCheck{{$appPermission->id}}"></label>
                              </div>
                            </td>
                            <td class="text-center" style="max-width: 10px;">
                               {{$i++}}
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$appPermission->name}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-dot mr-4">
                                  @if($appPermission->is_active == 1)
                                    <span class="d-none not_ap_ms"><i class="bg-danger"></i> Unpublish</span>
                                    <span class="approved_ms"><i class="bg-success"></i> Publish</span>
                                  @else
                                    <span class="not_ap_ms"><i class="bg-danger"></i> Unpublish</span>
                                    <span class="d-none approved_ms"><i class="bg-success"></i> Publish</span>
                                  @endif
                                 </span>
                            </td>
                            <td class="text-right">
                              @if($appPermission->is_active == 1)
                                
                                <button  class="btn btn-icon btn-2 btn-danger btn-sm activeInactivePermission inactive_cls" type="button" data-id="{{$appPermission->id}}" data-status='0' data-toggle="tooltip" data-placement="top" title="Inactive">
                                  <span class="btn-inner--icon">Unpublish</span>
                                </button>

                                <button class="btn btn-icon btn-2 btn-success btn-sm activeInactivePermission d-none active_cls" type="button" data-id="{{$appPermission->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Active">
                                  <span class="btn-inner--icon">Publish</span>
                                </button>

                              @else

                                <button class="btn btn-icon btn-2 btn-success btn-sm activeInactivePermission inactive_cls" type="button" data-id="{{$appPermission->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Active"><span class="btn-inner--icon">Publish</span>
                                </button>

                                <button class="btn btn-icon btn-2 btn-danger btn-sm activeInactivePermission d-none active_cls" type="button" data-id="{{$appPermission->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Inactive"><span class="btn-inner--icon">Unpublish</span>
                                </button>

                              @endif
                                <!--button class="btn btn-icon btn-2 
                                @if($appPermission->is_active == 1) 
                                    btn-success 
                                @else 
                                    btn-danger 
                                @endif 
                                btn-sm activeInactivePermission" data-toggle="tooltip" data-placement="top" title="
                                @if($appPermission->is_active == 1) 
                                    Active 
                                @else 
                                    In-active 
                                @endif" 
                                data-status="@if($appPermission->is_active == 1) 0 @else 1 @endif"
                                data-id="{{$appPermission->id}}">
                                    <span class="btn-inner--icon"><i class=" ni @if($appPermission->is_active != 1) ni-fat-remove @else ni-check-bold @endif"></i></span>
                                </button-->
                                <a class="btn btn-icon btn-2 btn-info btn-sm" href="{{url('admin/editAppPermission')}}/{{base64_encode($appPermission->id)}}" data-toggle="tooltip" data-placement="top" title="Edit">
                                  <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                </a>
                                <button class="btn btn-icon btn-2 btn-danger btn-sm delete_app_permission" type="button" data-id="{{$appPermission->id}}" data-toggle="tooltip" data-placement="top" title="Delete">
                                  <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                </button>
                            </td>
                       </tr>
                       @endforeach
                     @else
                       <tr>
                         <th colspan="12">
                           <div class="media-body text-center">
                               <span class="mb-0 text-sm">No Permission found.</span>
                           </div>
                         </th>
                       </tr>
                     @endif
                   </tbody>
                 </table>
               </div>
               <div class="ads_pagination mt-3 mb-0">
                   {{$appPermissions->appends(request()->except('page'))->links()}}
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