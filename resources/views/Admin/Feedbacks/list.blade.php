@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Feedbacks list')
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
               <h5 class="heading-small text-muted mb-4">Feedbacks List</h5>
            </div>
            
            <div class="col-lg-12">
                @include('flash-message')
               <div class="table-responsive">
                 <table class="table align-items-center text-center" id="activityTable">
                   <thead class="thead-light">
                     <tr>
                        <th scope="col" style="width: 10px;">Sr.No</th>
                        <th scope="col" class="text-center">Description</th>
                        {{-- <th scope="col" class="text-center">Image</th> --}}
                        <th scope="col" class="text-center">Rating</th>
                        <th scope="col" class="text-center">User</th>
                        <th scope="col" class="text-center">Role</th>
                    </tr>
                   </thead>
                   <tbody>
                      @if(count($feedbacks)>0)
                        @php
                            $i = ($feedbacks->currentpage()-1)* $feedbacks->perpage() + 1;
                        @endphp
                       @foreach($feedbacks as $feedback)
                       <tr>
                            <td class="text-center" style="max-width: 10px;">
                               {{$i++}}
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$feedback->description}}</span>
                                </div>
                            </td>
                            
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$feedback->feedback_rating}}</span>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$feedback->user_name_role['name']}}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media-body">
                                   <span class="mb-0 text-sm">{{$feedback->user_name_role['role']}}</span>
                                </div>
                            </td>
                            
                            
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
                   {{$feedbacks->appends(request()->except('page'))->links()}}
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