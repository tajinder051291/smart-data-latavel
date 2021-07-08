@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Users list')
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
               <h5 class="heading-small text-muted mb-4">Users List</h5>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{url('/admin/addUser')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Users</a>
            </div>
             
            <div class="col-lg-12">
                @include('flash-message')
              <div class="table-responsive">
                 <table class="table align-items-center text-center" id="activityTable">
                   <thead class="thead-light">
                     <tr>
                        <th scope="col" style="width: 10px;">Sr.No</th>
                        <th scope="col" style="width: 10px;">Image</th>
                        <th scope="col" class="text-center">Name</th>
                        <th scope="col" class="text-center">Email</th>
                        <th scope="col" class="text-center">Phone Number</th>
                        <th scope="col" class="text-center">State</th>
                        <th scope="col" class="text-center">User Role</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                   </thead>
                   <tbody>
                      @if(count($users)>0)
                        @php
                            $i = ($users->currentpage()-1)* $users->perpage() + 1;
                        @endphp
                       @foreach($users as $user)
                       <tr>
                            <td class="text-center" style="max-width: 10px;">
                               {{$i++}}
                            </td>

                            <td>
                                <a class="flex-column align-items-start py-1">
                                  <div class="d-flex">
                                      <div class="content-text ml-3">
                                          <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$user->image}}">
                                              <img src="{{$user->image}}" width="100" alt="" />
                                          </a>
                                      </div>
                                  </div>
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$user->name}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$user->email}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$user->phone_number}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$user->states->state}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$user->userRole->name}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-dot mr-4">
                                   @if($user->is_active == 1)
                                    <span class="d-none not_ap_ms"><i class="bg-danger"></i> Deactive</span>
                                   <span class="approved_ms"><i class="bg-success"></i> Active</span>
                                   @else
                                   <span class="not_ap_ms"><i class="bg-danger"></i>Deactive</span>
                                   <span class="d-none approved_ms"><i class="bg-success"></i> Active</span>
                                   @endif
                                 </span>
                            </td>
                          <!-- <td class="text-center">
                            <div class="media-body">
                                <span class="mb-0 text-sm">{{$user->categories}}</span>
                            </div>
                          </td> -->
                          <td class="text-right">
                            @if($user->is_active == 1)
                                
                                <button  class="btn btn-icon btn-2 btn-danger btn-sm activityUserOrNot inactive_cls" type="button" data-id="{{$user->id}}" data-status='0' data-toggle="tooltip" data-placement="top" title="Deactivate">
                                  <span class="btn-inner--icon">Deactivate</span>
                                </button>

                                <button class="btn btn-icon btn-2 btn-success btn-sm activityUserOrNot d-none active_cls" type="button" data-id="{{$user->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Activate">
                                  <span class="btn-inner--icon">Activate</span>
                                </button>

                              @else

                                <button class="btn btn-icon btn-2 btn-success btn-sm activityUserOrNot inactive_cls" type="button" data-id="{{$user->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Activate"><span class="btn-inner--icon">Activate</span>
                                </button>

                                <button class="btn btn-icon btn-2 btn-danger btn-sm activityUserOrNot d-none active_cls" type="button" data-id="{{$user->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Deactivate"><span class="btn-inner--icon">Deactivate</span>
                                </button>

                            @endif
                             <a class="btn btn-icon btn-2 btn-info btn-sm" href="{{url('admin/editUser')}}/{{base64_encode($user->id)}}" data-toggle="tooltip" data-placement="top" title="Edit">
                               <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                             </a>
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
                   {{$users->appends(request()->except('page'))->links()}}
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