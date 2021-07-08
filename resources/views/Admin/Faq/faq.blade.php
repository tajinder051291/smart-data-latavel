@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | FAQs list')
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
               <h5 class="heading-small text-muted mb-4">FAQs List</h5>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{url('/admin/addfaq')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add FAQ</a>
            </div>
             
            <div class="col-lg-12">
                @include('flash-message')
               <div class="table-responsive">
                 <table class="table align-items-center text-center" id="activityTable">
                   <thead class="thead-light">
                     <tr>
                        <th scope="col" style="width: 10px;">Sr.No</th>
                        <th scope="col" class="text-center">Title</th>
                        <th scope="col" class="text-center">Description</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                   </thead>
                   <tbody>
                      @if(count($faqs)>0)
                        @php
                            $i = ($faqs->currentpage()-1)* $faqs->perpage() + 1;
                        @endphp
                       @foreach($faqs as $faq)
                       <tr>
                            <td class="text-center" style="max-width: 10px;">
                               {{$i++}}
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$faq->title}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   {{-- <span class="mb-0 text-sm">{!! $faq->description !!}</span> --}}
                                   <span class="mb-0 text-sm">{!! Str::limit($faq->description, 25); !!}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-dot mr-4">
                                   @if($faq->is_active == 1)
                                    <span class="d-none not_ap_ms"><i class="bg-danger"></i> Unpublish</span>
                                   <span class="approved_ms"><i class="bg-success"></i> Publish</span>
                                   @else
                                   <span class="not_ap_ms"><i class="bg-danger"></i> Unpublish</span>
                                   <span class="d-none approved_ms"><i class="bg-success"></i> Publish</span>
                                   @endif
                                 </span>
                            </td>
                            <td class="text-right">
                              @if($faq->is_active == 1)
                                
                                <button  class="btn btn-icon btn-2 btn-danger btn-sm activeInactiveFaq inactive_cls" type="button" data-id="{{$faq->id}}" data-status='0' data-toggle="tooltip" data-placement="top" title="Unpublish">
                                  <span class="btn-inner--icon">Unpublish</span>
                                </button>

                                <button class="btn btn-icon btn-2 btn-success btn-sm activeInactiveFaq d-none active_cls" type="button" data-id="{{$faq->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Publish">
                                  <span class="btn-inner--icon">Publish</span>
                                </button>

                              @else

                                <button class="btn btn-icon btn-2 btn-success btn-sm activeInactiveFaq inactive_cls" type="button" data-id="{{$faq->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Publish"><span class="btn-inner--icon">Publish</span>
                                </button>

                                <button class="btn btn-icon btn-2 btn-danger btn-sm activeInactiveFaq d-none active_cls" type="button" data-id="{{$faq->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Unpublish"><span class="btn-inner--icon">Unpublish</span>
                                </button>

                              @endif
                              <a class="btn btn-icon btn-2 btn-info btn-sm" href="{{url('admin/editFaq')}}/{{base64_encode($faq->id)}}" data-toggle="tooltip" data-placement="top" title="Edit">
                                 <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                               </a>
                               <!--button class="btn btn-icon btn-2 btn-danger btn-sm delete_role" type="button" data-role-id="{{$faq->id}}" data-toggle="tooltip" data-placement="top" title="Delete">
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
                   {{$faqs->appends(request()->except('page'))->links()}}
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