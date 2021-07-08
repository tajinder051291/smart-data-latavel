@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | App Mobile Models list')
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
               <h5 class="heading-small text-muted mb-4">Mobile Models</h5>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{url('admin/addMobileModels')}}/{{$brandId}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Mobile Models</a>
            </div>
             
            <div class="col-lg-12">
                @include('flash-message')
               <div class="table-responsive">
                 <table class="table align-items-center text-center" id="activityTable">
                   <thead class="thead-light">
                     <tr>
                        <th scope="col" style="width: 10px;">Sr.No</th>
                        <th scope="col" class="text-center">Brand</th>
                        <th scope="col" class="text-center">Model</th>
                        <th scope="col" class="text-center">Specification</th>
                        <th scope="col" class="text-center">Color</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                   </thead>
                   <tbody>
                      @if(count($mobileModels)>0)
                        @php
                            $i = ($mobileModels->currentpage()-1)* $mobileModels->perpage() + 1;
                        @endphp
                       @foreach($mobileModels as $mobileModel)
                       <tr>
                            <td class="text-center" style="max-width: 10px;">
                               {{$i++}}
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$brand->brand_name}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$mobileModel->model}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$mobileModel->specification}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$mobileModel->color}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-dot mr-4">
                                  @if($mobileModel->is_active == 1)
                                    <span class="d-none not_ap_ms"><i class="bg-danger"></i> Deactivate</span>
                                    <span class="approved_ms"><i class="bg-success"></i> Activate</span>
                                  @else
                                    <span class="not_ap_ms"><i class="bg-danger"></i> Deactivate</span>
                                    <span class="d-none approved_ms"><i class="bg-success"></i> Activate</span>
                                  @endif
                                 </span>
                            </td>
                            <td class="text-right">
                              @if($mobileModel->is_active == 1)
                                
                                <button  class="btn btn-icon btn-2 btn-danger btn-sm activeInactiveModels inactive_cls" type="button" data-id="{{$mobileModel->id}}" data-status='0' data-toggle="tooltip" data-placement="top" title="Deactivate">
                                  <span class="btn-inner--icon">Deactivate</span>
                                </button>

                                <button class="btn btn-icon btn-2 btn-success btn-sm activeInactiveModels d-none active_cls" type="button" data-id="{{$mobileModel->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Activate">
                                  <span class="btn-inner--icon">Activate</span>
                                </button>

                              @else

                                <button class="btn btn-icon btn-2 btn-success btn-sm activeInactiveModels inactive_cls" type="button" data-id="{{$mobileModel->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Activate"><span class="btn-inner--icon">Activate</span>
                                </button>

                                <button class="btn btn-icon btn-2 btn-danger btn-sm activeInactiveModels d-none active_cls" type="button" data-id="{{$mobileModel->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Deactivate"><span class="btn-inner--icon">Deactivate</span>
                                </button>

                              @endif
                                <!--button class="btn btn-icon btn-2 
                                @if($mobileModel->is_active == 1) 
                                    btn-success 
                                @else 
                                    btn-danger 
                                @endif 
                                btn-sm activeInactiveModels" data-toggle="tooltip" data-placement="top" title="
                                @if($mobileModel->is_active == 1) 
                                    Active 
                                @else 
                                    In-active 
                                @endif" 
                                data-status="@if($mobileModel->is_active == 1) 0 @else 1 @endif"
                                data-model-id="{{$mobileModel->id}}">
                                    <span class="btn-inner--icon"><i class=" ni @if($mobileModel->is_active != 1) ni-fat-remove @else ni-check-bold @endif"></i></span>
                              </button-->
                                  <a class="btn btn-icon btn-2 btn-info btn-sm" href="{{url('admin/editMobileModels')}}/{{base64_encode($mobileModel->brand_id)}}/{{base64_encode($mobileModel->id)}}" data-toggle="tooltip" data-placement="top" title="Edit">
                                  <span class="btn-inner--icon">
                                    <i class="fas fa-edit"></i>
                                  </span>
                                </a>
                                <!--button class="btn btn-icon btn-2 btn-danger btn-sm delete_mobile_model" type="button" data-id="{{$mobileModel->id}}" data-toggle="tooltip" data-placement="top" title="Delete">
                                  <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                </button-->
                            </td>
                       </tr>
                       @endforeach
                     @else
                       <tr>
                         <th colspan="12">
                           <div class="media-body text-center">
                               <span class="mb-0 text-sm">No Data found.</span>
                           </div>
                         </th>
                       </tr>
                     @endif
                   </tbody>
                 </table>
               </div>
               <div class="ads_pagination mt-3 mb-0">
                   {{$mobileModels->appends(request()->except('page'))->links()}}
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