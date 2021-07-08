@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Update invoice payment details')
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
               <h5 class="heading-small text-muted mb-4">Update invoice payment details</h5>
             </div>
             <div class="col-md-6 text-right">
               <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary"><i class="ni ni-bold-left"></i> &nbsp;Back</a>
             </div>
                <div class="col-lg-12">
                    @include('flash-message')
                    <form action="{{ url('/admin/invoice/payment/update') .'/'. base64_encode($invoice->id )}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Order id</label>
                                <input type="number" class="form-control" value="{{ $invoice->order_id }}" disabled>
                                <input type="hidden" name="order_id" value="{{ $invoice->order_id }}" hidden>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Invoice number</label>
                                <input type="number" class="form-control" name="invoice_number" value="{{ $invoice->invoice_number }}" disabled>
                                <input type="hidden" name="invoice_number" value="{{ $invoice->invoice_number }}" hidden>
                              </div>
                          </div>
                         
                          <div class="col-md-6">
                              <div class="form-group">
                                <label class="form-control-label" for="name">Payment details</label>
                                <input required type="text" class="form-control" placeholder="Transaction No. or UTR No." name="payment_details" value="{{ old('payment_details') }}">
                              </div>
                          </div>
                         
                          <div class="col-lg-6 mb-6">
                            <div class="blog_image">
                              <div class="avatar-upload">
                                  <label class="form-control-label" for="name">Payment attachment</label>

                                  <div class="avatar-edit">
                                      <input type='file' id="imageUpload1" accept=".png, .jpg, .jpeg, .gif" name="payment_attachment"/>
                                      <input type="hidden" name="payment_attachment" value=""/>
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