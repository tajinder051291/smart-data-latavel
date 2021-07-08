@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Edit Mobile Brands')
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
               <h5 class="heading-small text-muted mb-4">Edit Mobile Brand</h5>
             </div>
             <div class="col-md-6 text-right">
               <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary"><i class="ni ni-bold-left"></i> &nbsp;Back</a>
             </div>
            @if($mobileModel)
                <div class="col-lg-12">
                    @include('flash-message')
                    <form action="{{ url('/admin/editMobileModels') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $mobileModel->id }}" name="id">
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Model</label>
                                <input required type="text" class="form-control" placeholder="Model" name="model" value="{{$mobileModel->model}}">
                              </div>
                          </div> 

                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Specification</label>
                                <input required type="text" class="form-control" placeholder="8/64" name="specification" value="{{$mobileModel->specification}}">
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Color</label>
                                <input required type="text" class="form-control" placeholder="Color like White or Grey" name="color" value="{{$mobileModel->color}}">
                              </div>
                          </div>

                          <!--div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">RAM</label>
                                <input required type="text" class="form-control" placeholder="RAM" name="ram" value="{{$mobileModel->ram}}">
                              </div>
                          </div> 

                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Storage</label>
                                <input required type="text" class="form-control" placeholder="Storage" name="storage" value="{{$mobileModel->storage}}">
                              </div>
                          </div-->  

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