@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Edit Role')
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
            <div class="col-md-6">
               <h5 class="heading-small text-muted mb-4">Edit Role</h5>
             </div>
             <div class="col-md-6 text-right">
               <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary"><i class="ni ni-bold-left"></i> &nbsp;Back</a>
             </div>
            @if($roles)
                <div class="col-lg-12">
                    @include('flash-message')
                    <form action="{{ url('/admin/editRole') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $roles->id }}" name="id">
                        <div class="row">

                           <div class="col-md-12">
                              <label class="form-control-label" for="name">Permissions</label>
                              <div class="form-group">
                                  <select required class="form-control select4" name="appPermissionIds[]" multiple id="states">

                                    @foreach($appPermissions as $appPermission)
                                        <?php if($roles->app_permission_id){  ?>
                                                <option <?php 
                                                    foreach(explode(',', $roles->app_permission_id) as $permissions){
                                                        if($permissions==$appPermission->id){ 
                                                            echo 'selected';
                                                        }
                                                    } ?>  
                                                    value="{{$appPermission->id}}">
                                                    {{$appPermission->name}}
                                                </option>
                                        <?php }else{ ?>
                                            <option value="{{$appPermission->id}}">{{$appPermission->name}}</option>
                                        <?php } ?>
                                    @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Name</label>
                                <input required type="text" minlength="3" maxlength="90" class="form-control txtOnly" placeholder="Name" name="name" value="{{$roles->name}}">
                              </div>
                          </div>
                          <div class="col-md-12">
                                <div class="form-group">
                                <button class="btn btn-primary" type="submit">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
		    	</div>
		    </div>
		</div>
    <!-- Footer Section Include -->
        @include('Admin.Layouts.footer')
    <!-- End Footer Section Include -->
  </div>
</div>
@endsection