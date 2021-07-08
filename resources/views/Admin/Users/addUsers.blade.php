@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Add User')
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
               <h5 class="heading-small text-muted mb-4">Add User</h5>
             </div>
             <div class="col-md-6 text-right">
               <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary"><i class="ni ni-bold-left"></i> &nbsp;Back</a>
             </div>
                <div class="col-lg-12">
                    @include('flash-message')
                    <form action="{{ url('/admin/addUser') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                          <div class="col-md-4">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Name</label>
                                <input required type="text" minlength="3" maxlength="90" class="form-control txtOnly" placeholder="Name" name="name" value="">
                              </div>
                          </div>  

                          <div class="col-md-4">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Email</label>
                                <input required type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" placeholder="Email" name="email" value="">
                              </div>
                          </div>

                          <div class="col-md-4">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Phone Number</label>
                                <input required type="text" maxlength="10" class="form-control" placeholder="Phone Number" name="phone_number" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57" value="">
                              </div>
                          </div>

                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="form-control-label" for="name">User Role</label>
                              <select required class="form-control button_select" name="user_role" required="required">
                                @foreach ($userRole as $role)
                                    @if( $role->type == 1 )
                                    <option value="{{ $role->id }}" >{{ $role->name }}</option>
                                    @endif
                                @endforeach
                              </select>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="form-control-label" for="name">State</label>
                              <select required class="form-control button_select" name="state" required="required">
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" >{{ $state->state }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>

                           <div class="col-lg-6 mb-4">
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