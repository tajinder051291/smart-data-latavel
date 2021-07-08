@extends('Admin.Layouts.loginsignuplayout')
@section('content')
  <!-- Header -->
  <div class="header bg-gradient-primary py-4 py-lg-5">
    <div class="container">
    <div class="header-body text-center mb-7">
        <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6">
            <h1 class="text-white">Welcome to Smartebiz!</h1>
            <p class="text-lead text-light">Sign up and manage the app from admin panel</p>
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
        <!-- <div class="card-header bg-transparent pb-5">
            <div class="text-muted text-center mt-2 mb-3"><small>Sign in with</small></div>
            <div class="btn-wrapper text-center">
            <a href="#" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="../assets/img/icons/common/github.svg"></span>
                <span class="btn-inner--text">Github</span>
            </a>
            <a href="#" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="../assets/img/icons/common/google.svg"></span>
                <span class="btn-inner--text">Google</span>
            </a>
            </div>
        </div> -->
        <div class="card-body px-lg-5 py-lg-5">
            <div class="navbar-brand text-center mb-4">
                <img style="width:50%;" src="{{URL::asset('assets/img/logo.png')}}" />
            </div>
            <div class="text-center text-muted mb-4">
                <small>Sign up with credentials</small>
            </div>

            @include('flash-message')
            <form method="post" id="registerForm">
                @csrf
                <div class="form-group">
                    <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                    </div>
                    <input class="form-control" placeholder="Name" type="text" name="name" required="required">  
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" placeholder="Email" type="email" name="email" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Password" type="password" name="password" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Confirm Password" type="password" name="password_confirmation">
                    </div>
                </div>
                <!-- <div class="text-muted font-italic"><small>password strength: <span class="text-success font-weight-700">strong</span></small></div> -->
                <div class="row my-4">
                    <div class="col-12">
                    <div class="custom-control custom-control-alternative custom-checkbox">
                        <input class="custom-control-input" id="customCheckRegister" type="checkbox" required>
                        <label class="custom-control-label" for="customCheckRegister">
                        <span class="text-muted">I agree with the <a href="javascript:void(0)">Privacy Policy</a></span>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-4">Create account</button>
                </div>
            </form>
        </div>
        </div>
        
    </div>
    </div>
</div>

@endsection