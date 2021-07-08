@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Add Delivery Partners')
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
               <h5 class="heading-small text-muted mb-4">Add Delivery Partners</h5>
             </div>
             <div class="col-md-6 text-right">
               <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary"><i class="ni ni-bold-left"></i> &nbsp;Back</a>
             </div>
                <div class="col-lg-12">
                    @include('flash-message')
                    <form action="{{ url('/admin/addDeliveryPartners') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Company Name</label>
                                <input required type="text" minlength="3" maxlength="90" class="form-control txtOnly" placeholder="Name" name="company_name" value="{{ old('company_name') }}">
                              </div>
                          </div>

                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Contact Person Name</label>
                                <input required type="text" minlength="3" maxlength="90" class="form-control txtOnly" placeholder="Name" name="name" value="{{ old('name') }}">
                              </div>
                          </div> 

                           <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Contact Email</label>
                                <input required type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
                              </div>
                          </div> 

                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Contact Phone Number</label>
                                <input required type="text" maxlength="10" class="form-control" placeholder="Phone Number" name="phone_number" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57" value="{{ old('phone_number') }}">
                              </div>
                          </div>

                          <div class="col-md-12">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Address</label>
                                <input required type="text" class="form-control" placeholder="Address" name="address" value="{{ old('address') }}">
                              </div>
                          </div>

                           <div class="col-lg-6 mb-6">
                              <div class="blog_image">
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' id="imageUpload1" accept=".png, .jpg, .jpeg, .gif" name="display_image"/>
                                        <input type="hidden" name="oldOdisplayImg" value=""/>
                                        <label for="imageUpload1"><i class="fas fa-edit"></i></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview1" style="background-image: url({{URL::asset('assets/img/thumbnail-default_2.jpg')}});">
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                <button class="btn btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
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