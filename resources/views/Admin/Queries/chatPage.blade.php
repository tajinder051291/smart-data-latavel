@extends('Admin.Layouts.mainlayout')
@section('title', 'Admin | Close tickets list')
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
                        <h5 class="heading-small text-muted mb-4">Query Details</h5>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{url('/admin/query/list/2')}}" class="btn btn-primary btn-sm"><i class="ni ni-bold-left"></i> Back</a>
                        @if($ticketDetail->is_active == 1)
                            <a href="{{url('/admin/query/close')}}/{{base64_encode($ticketDetail->id)}}" onclick="return confirm('Do you really want to close this ticket ?')" class="btn btn-success btn-sm"> Close Ticket</a>
                        {{-- @else
                            <span class="heading-small text-muted">Closed</span> --}}
                        @endif
                    </div>
                    @if($ticketDetail)
                    <div class="col-lg-12">
                        <div class="ticketinfo">
                            <div class="row p-2">
                                <div class="col-lg-12">
                                    <!--List with title and linked content -->
                                    <div class="list-group pmd-list pmd-z-depth-1">
                                        <a class="h-35 flex-column align-items-start py-1">
                                            <div class="d-flex">
                                                <h4 class="pmd-list-title mb-0"><strong>Status</strong></h4>: &nbsp;&nbsp;&nbsp;<span class="content-text">{{$ticketDetail->is_active ? 'Open':'Closed'}}</span>
                                            </div>
                                        </a>
                                        <a class="h-35 flex-column align-items-start py-1">
                                            <div class="d-flex">
                                                <h4 class="pmd-list-title mb-0"><strong>Phone Number</strong></h4>: &nbsp;&nbsp;&nbsp;<span class="content-text">{{$ticketDetail->user_details['phone_number']}}</span>
                                            </div>
                                        </a>
                                        <a class="h-35 flex-column align-items-start py-1">
                                            <div class="d-flex">
                                                <h4 class="pmd-list-title mb-0"><strong>Subject</strong></h4>: &nbsp;&nbsp;&nbsp;<span class="content-text">{{$ticketDetail->subject}}</span>
                                            </div>
                                        </a>
                                        <a class="flex-column align-items-start py-1">
                                            <div class="d-flex">
                                                <h4 class="pmd-list-title mb-0"><strong>Description</strong></h4>: &nbsp;&nbsp;&nbsp;<span class="content-text">{{$ticketDetail->description}}</span>
                                            </div>
                                        </a>
                                        @if($ticketDetail->query_images != "")
                                        <a class="flex-column align-items-start py-1">
                                            <div class="d-flex">
                                                <h4 class="pmd-list-title mb-0"><strong>Attachments</strong></h4>
                                                <div class="content-text ml-3">
                                                    @foreach(explode(",",$ticketDetail->images) as $attach)
                                                    <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$attach}}">
                                                        <img src="{{$attach}}" alt="" />
                                                    </a>    
                                                    @endforeach
                                                </div>
                                            </div>
                                        </a>
                                        @endif 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-4">
                        <h5 class="heading-small text-muted ">Comments</h5>
                        @include('flash-message')
                        <div class="chatSection">
                          <div class="mesgs">
                              <div id="msg_history" class="msg_history">
                                @if(count($ticketDetail->comments) > 0)
                                    @foreach($ticketDetail->comments as $key => $comments)
                                        @if($comments->user_role == 7)
                                        <div class="incoming_msg">
                                            <div class="incoming_msg_img"> 
                                                <img src="{{$ticketDetail->user && $ticketDetail->user->profile_pic != '' ? $ticketDetail->user->profile_pic : URL::asset('/assets/img/user-profile.png')}}" alt=""> 
                                            </div>
                                            <div class="received_msg">
                                                @if($comments->images != "")
                                                    <div class="image-with-comment image-with-comment{{$key}}">
                                                        @foreach($comments->comment_images as $attach)
                                                            <a class="fancybox-thumb" data-fancybox-group="thumb" rel="fancybox-thumb" href="{{$attach}}">
                                                                <img src="{{$attach}}" alt="" />
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <div class="received_withd_msg">
                                                    @if($comments->comment != "")
                                                        <p>{{$comments->comment}}</p>
                                                    @endif
                                                    <span class="time_date"> {{date('h:i A | M d',strtotime($comments->created_at))}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @else
                                        <div class="outgoing_msg">
                                            <div class="sent_msg">
                                                <p>{{$comments->comment}}</p>
                                                <span class="time_date text-right">{{date('h:i A | M d',strtotime($comments->created_at))}}</span> 
                                            </div>
                                        </div>
                                        @endif

                                        <script>
                                            $('.image-with-comment{{$key}} .fancybox-thumb').slice(4).css('display','none');
                                            var count = $('.image-with-comment{{$key}} .fancybox-thumb').length;
                                            var pending_count = count - 4;
                                            if(pending_count > 0){
                                                $('.image-with-comment{{$key}} .fancybox-thumb:nth-child(4)').append('<div class="overlay-count">+'+pending_count+'</div>')
                                            }
                                        </script>
                                        
                                    @endforeach
                                @endif
                              </div>
                              @if($ticketDetail->is_active == 1)
                              <div class="type_msg">
                                  <div class="input_msg_write">
                                      <input id="myInputText" style="padding: 10px;" type="text" class="write_msg text_msg" placeholder="Type a message" required="required"/>
                                      <small class="error">Field is required.</small>
                                      <button id="send_msg_btn" style="margin-right:10px" class="msg_send_btn send_msg" type="button" data-url="/admin/query/message" data-ticketId="{{base64_encode($ticketDetail->id)}}"><i class="ni ni-send" aria-hidden="true"></i></button>
                                  </div>
                              </div>
                              @endif
                          </div>
                      </div>
                    </div>
                    @else
                    <div class="col-lg-12 text-center">
                        <div class="media-body text-center">
                            <span class="mb-0 text-sm">No data found.</span>
                        </div>
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
<script>
    var input = document.getElementById("myInputText");
    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("send_msg_btn").click();
        }
    });
    var objDiv = document.getElementById("msg_history");
    objDiv.scrollTop = objDiv.scrollHeight;
</script>
 <script>
    $('.content-text .fancybox-thumb').slice(11).css('display','none');
    var count = $('.content-text .fancybox-thumb').length;
    var pending_count = count - 11;
    if(pending_count > 0){
        $('.content-text .fancybox-thumb:nth-child(12)').append('<div class="overlay-count">+'+pending_count+'</div>');
    }
</script>
{{-- <script>
    $('.image-with-comment{{$key}} .fancybox-thumb').slice(4).css('display','none');
    var count = $('.image-with-comment{{$key}} .fancybox-thumb').length;
    var pending_count = count - 4;
    if(pending_count > 0){
        $('.image-with-comment{{$key}} .fancybox-thumb:nth-child(4)').append('<div class="overlay-count">+'+pending_count+'</div>')
    }
</script> --}}
@endsection