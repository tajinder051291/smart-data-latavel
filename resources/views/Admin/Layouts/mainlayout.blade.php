<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smartebiz - @yield('title')</title>
    <!-- Favicon -->
    <link href="{{URL::asset('assets/img/brand/favicon.png')}}" rel="icon" type="image/png">
    <!-- CSS Section Include -->
        @include('Admin.Layouts.allcss')
    <!-- End CSS Section Include -->
  </head>

  <body>
    <div class="loader-div">
    
      <svg class="loader" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340 340">
        <circle cx="170" cy="170" r="160" stroke="#7a61e4"/>
        <circle cx="170" cy="170" r="135" stroke="#404041"/>
        <circle cx="170" cy="170" r="110" stroke="#7a61e4"/>
        <circle cx="170" cy="170" r="85" stroke="#404041"/>
      </svg>
      
    </div>
    <!-- Header Section Include -->
        @include('Admin.Layouts.header')
    <!-- End Header Section Include -->
    <!-- Content Section Include -->
        @yield('content')
    <!-- End Content Section Include -->
    <!-- Script Section  -->
        @include('Admin.Layouts.alljquery')
    <!-- End Script Section  -->
    <!-- Custome Script Section  -->
        @include('Admin.Layouts.js.settings_js')
    <!-- Custome Script Section  -->
    <!-- Tickets Page script Section -->
        @include('Admin.Layouts.js.tickets_js')
    <!-- End Tickets Page script Section -->

    <div class="sendEmailModalOverLay"></div>
  </body>

</html>