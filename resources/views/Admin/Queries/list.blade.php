@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Queries list')
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
               <h5 class="heading-small text-muted mb-4">Queries List</h5>
            </div>
            
            <div class="col-lg-12">
                @include('flash-message')
               <div class="table-responsive">
                 <table class="table align-items-center text-center" id="activityTable">
                   <thead class="thead-light">
                     <tr>
                        <th scope="col" style="width: 10px;">Sr.No</th>
                        <th scope="col" class="text-center">Subject</th>
                        {{-- <th scope="col" class="text-center">Description</th> --}}
                        {{-- <th scope="col" class="text-center">Images</th> --}}
                        <th scope="col" class="text-center">From</th>
                        <th scope="col" class="text-center">Role</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-right">Unread comments<i class="fas fa-comment"></i></th>
                        <th scope="col" class="text-right">Details</th>
                    </tr>
                   </thead>
                   <tbody>
                      @if(count($queries)>0)
                        @php
                            $i = ($queries->currentpage()-1)* $queries->perpage() + 1;
                        @endphp
                       @foreach($queries as $query)
                       <tr>
                            <td class="text-center" style="max-width: 10px;">
                               {{$i++}}
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$query->subject}}</span>
                                </div>
                            </td>
                            {{-- <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$query->description}}</span>
                                </div>
                            </td> --}}
                            {{-- <td class="text-center">
                                <div class="media-body">
                                  @foreach($query->query_images as $image)
                                    <img src="{{$image}}" width="100">
                                  @endforeach
                                </div>
                            </td> --}}
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{ucfirst($query->user_details['name'])}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{ucfirst($query->user_details['role'])}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-dot mr-4">
                                   @if($query->is_active == 1)
                                    <span class="d-none not_ap_ms"><i class="bg-danger"></i> Closed</span>
                                   <span class="approved_ms"><i class="bg-success"></i> Open</span>
                                   @else
                                   <span class="not_ap_ms"><i class="bg-danger"></i> Closed</span>
                                   <span class="d-none approved_ms"><i class="bg-success"></i> Open</span>
                                   @endif
                                 </span>
                            </td>

                             <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$query->unread_seller_comments_count}}</span>
                                </div>
                            </td>


                            <td class="text-right">

                                {{-- @if($query->is_active == 1)
                                  
                                  <button  class="btn btn-icon btn-2 btn-danger btn-sm 
                                    activeInactiveQuery inactive_cls" type="button" data-id="{{$query->id}}" data-status='0' data-toggle="tooltip" data-placement="top" title="Close">
                                    <span class="btn-inner--icon">Close</span>
                                  </button>

                                  <button class="btn btn-icon btn-2 btn-success btn-sm activeInactiveQuery
                                    d-none active_cls" type="button" data-id="{{$query->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Open">
                                    <span class="btn-inner--icon">Open</span>
                                  </button>

                                @else
                                  <button class="btn btn-icon btn-2 btn-success btn-sm 
                                      activeInactiveQuery
                                      inactive_cls" type="button" data-id="{{$query->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Open"><span class="btn-inner--icon">Open</span>
                                  </button>

                                  <button class="btn btn-icon btn-2 btn-danger btn-sm 
                                      activeInactiveQuery
                                      d-none active_cls" type="button" data-id="{{$query->id}}" data-status='1' data-toggle="tooltip" data-placement="top" title="Close"><span class="btn-inner--icon">Close</span>
                                  </button>

                                @endif --}}

                              <a class="btn btn-icon btn-2 btn-info btn-sm" href="{{url('admin/query/details')}}/{{base64_encode($query->id)}}" data-toggle="tooltip" data-placement="top" title="Details">
                                <span class="btn-inner--icon"><i class="fas fa-info-circle"></i></span>
                              </a>
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
                   {{$queries->appends(request()->except('page'))->links()}}
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