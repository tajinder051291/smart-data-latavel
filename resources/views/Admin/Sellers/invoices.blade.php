@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Invoices list')
<script src="{{URL::asset('assets/js/plugins/jquery/dist/jquery.min.js')}}"></script>
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
               <h5 class="heading-small text-muted mb-4">Invoices List</h5>
            </div>
             
            <div class="col-lg-12">
                @include('flash-message')
               <div class="table-responsive">
                 <table class="table align-items-center text-center" id="activityTable">
                   <thead class="thead-light">
                     <tr>
                        <th scope="col" style="width: 10px;">Sr.No</th>
                        <th scope="col" class="text-center">Invoice number</th>
                        <th scope="col" class="text-center">Invoice date</th>
                        <th scope="col" class="text-center">Invoice amount</th>
                        <th scope="col" class="text-center">Bank details</th>
                        <th scope="col" class="text-center">Payment attachment</th>
                        <th scope="col" class="text-center">Payment details</th>
                        <th scope="col" class="text-center">Status</th>
                    </tr>
                   </thead>
                   <tbody>
                      @if(count($invoices)>0)
                        @php
                            $i = ($invoices->currentpage()-1)* $invoices->perpage() + 1;
                        @endphp
                       @foreach($invoices as $invoice)
                       <tr>
                            <td class="text-center" style="max-width: 10px;">
                               {{$i++}}
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$invoice->invoice_number}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$invoice->invoice_date}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$invoice->invoice_amount}}</span>
                                </div>
                            </td>

                            <td>
                                <a class="flex-column align-items-start py-1">
                                  <div class="d-flex">
                                      <div class="content-text ml-3">
                                          <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$invoice->bank_details}}">
                                              <img src="{{$invoice->bank_details}}" width="100" alt="" />
                                          </a>
                                      </div>
                                  </div>
                                </a>
                            </td>

                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$invoice->payment_details}}</span>
                                </div>
                            </td>
                            <td>
                                <a class="flex-column align-items-start py-1">
                                  <div class="d-flex">
                                      <div class="content-text ml-3">
                                          <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$invoice->payment_attachment}}">
                                              <img src="{{$invoice->payment_attachment}}" width="100" alt="" />
                                          </a>
                                      </div>
                                  </div>
                                </a>
                            </td>
                             <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">@if($invoice->invoice_status) {{'Paid'}} @else {{'Pending'}} @endif</span>
                                </div>
                            </td>

                           
                       </tr>
                       @endforeach
                     @else
                       <tr>
                         <th colspan="12">
                           <div class="media-body text-center">
                               <span class="mb-0 text-sm">No Invoice found.</span>
                           </div>
                         </th>
                       </tr>
                     @endif
                   </tbody>
                 </table>
               </div>
               <div class="ads_pagination mt-3 mb-0">
                   {{$invoices->appends(request()->except('page'))->links()}}
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