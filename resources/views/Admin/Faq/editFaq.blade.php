@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Add FAQ')
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
               <h5 class="heading-small text-muted mb-4">Edit FAQ</h5>
             </div>
             <div class="col-md-6 text-right">
               <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary"><i class="ni ni-bold-left"></i> &nbsp;Back</a>
             </div>
            
            <div class="col-lg-12">
                @include('flash-message')
              <form action="{{ url('/admin/editFaq') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{$faq->id}}" name="id"/>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="text" required="required" class="form-control"  placeholder="Title" value="{{$faq->title}}" name="title">
                    </div>
                  </div>
                  
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description</label>
                      <textarea id="discription" type="text" class="form-control"  placeholder="Description" name="description">{{$faq->description}}</textarea>
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
          </div>
        </div>
    </div>
    <!-- Footer Section Include -->
        @include('Admin.Layouts.footer')
    <!-- End Footer Section Include -->
  </div>
</div>
@endsection