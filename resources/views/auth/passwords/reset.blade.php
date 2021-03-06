@extends('Admin.Layouts.loginsignuplayout')
@section('content')
  <!-- Header -->
  <div class="header bg-gradient-primary py-4 py-lg-5">
    <div class="container">
    <div class="header-body text-center mb-7">
        <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6">
            <h1 class="text-white"></h1>
            <p class="text-lead text-light"></p>
        </div>
        </div>
    </div>
    </div>
    <div class="separator separator-bottom separator-skew zindex-100">
    <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
    </svg>
    </div>
</div>
<!-- Page content -->
<div class="container mt--8 pb-5">
    <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary shadow border-0">
        <div class="card-body px-lg-5 py-lg-5">
            <div class="navbar-brand text-center">
                <img style="width:50%;" src="{{URL::asset('assets/img/logo.png')}}" />
            </div>
            <div class="text-center text-muted mb-4">
                <small>Reset Password</small>
            </div>

            @include('flash-message')
            <form action="{{ url('/resetPassword') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group mb-3">
                    
                    <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input required type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" class="form-control" placeholder="Email" name="email" value="{{$email}}">
                    </div>

                </div>
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><!--i class="ni ni-email-83"></i--></span>
                        </div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter New Password" name="password" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><!--i class="ni ni-email-83"></i--></span>
                        </div>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-primary my-4">Save Password</button>
                </div>
            </form>
        </div>
        </div>
        <div class="row mt-3">
        <div class="col-6">
            <!--a href="{{url('/signup')}}" class="text-light"><small>Create new account</small></a-->
            <!--a href="#" class="text-light"><small>Forgot password?</small></a-->
        </div>
        <div class="col-6 text-right">
            <a href="{{url('/')}}" class="text-light"><small></small></a>
        </div>
        </div>
    </div>
    </div>
</div>

@endsection