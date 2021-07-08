
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<!-- Icons -->
<link href="{{URL::asset('assets/js/plugins/nucleo/css/nucleo.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet" />
<!-- CSS Files -->
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
<link href="{{URL::asset('assets/css/argon-dashboard.css')}}" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="{{URL::asset('source/jquery.fancybox.css')}}" media="screen" />
<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="{{URL::asset('source/helpers/jquery.fancybox-buttons.css')}}" />
<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="{{URL::asset('source/helpers/jquery.fancybox-thumbs.css')}}" />
<style>
  span.select2-selection.select2-selection--single {
  font-size: .875rem !important;
  /* line-height: 1.5; */
  display: block;
  width: 100%;
  height: calc(2.75rem + 2px);
  padding: .625rem 5px;
  transition: all .2s cubic-bezier(.68, -.55, .265, 1.55);
  color: #8898aa;
  border: 1px solid #cad1d7;
  border-radius: .375rem;
  background-color: #fff;
  background-clip: padding-box;
  box-shadow: none;
}

.select2-container--default .select2-selection--single .select2-selection__rendered{
  color: #b3bac2 !important;
  line-height: unset !important;
}
span.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus {
  width: 100%;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
  top: 10px !important;
}
span.select2.select2-container.select2-container--default.select2-container--focus{
  width: 100% !important;
}
.select2-container--default .select2-selection--multiple{
  border: 1px solid #cad1d7 !important;
  padding: 5px 0px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice{
  background-color: #5d72e4 !important;
  border: 1px solid #5d72e4 !important;
  color: #fff !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
  color: #fff !important;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
  border: 1px solid #cad1d7 !important;
  padding: 5px 0px;
}
.enableDesable {
    text-align: right;
    display: inline-flex;
    float: right;
}
.enableDesable .custom-control {
    margin-left: 15px;
}
/*Image preview*/

.blog_image .avatar-upload {
  position: relative;
  max-width: 205px;
}
.blog_image .avatar-upload .avatar-edit {
  position: absolute;
  right: 12px;
  z-index: 1;
  top: 0px;
}
.blog_image .avatar-upload .avatar-edit input {
  display: none;
}
.blog_image .avatar-upload .avatar-edit input + label {
  display: inline-block;
  width: 34px;
  height: 34px;
  margin-bottom: 0;
  background: #ffffff;
  border: 1px solid transparent;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
  cursor: pointer;
  font-weight: normal;
  transition: all 0.2s ease-in-out;
}
.blog_image .avatar-upload .avatar-edit input + label:hover {
  background: #f1f1f1;
  border-color: #d6d6d6;
}
.blog_image .avatar-upload .avatar-edit input + label i {
  
  color: #757575;
  position: absolute;
  top: 7px;
  left: 3px;
  right: 0;
  text-align: center;
  margin: auto;
}
.blog_image .avatar-upload .avatar-preview {
  width: 192px;
  height: 192px;
  position: relative;
  border: 6px solid #f8f8f8;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
}
.blog_image .avatar-upload .avatar-preview > div {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}
/*End Image preview*/
/* User Transaction Modal Start */
.payAmount,.deletePermission{
  display:none;
}
#payAmountModal .modal-content{
	overflow:hidden;
}

#payAmountModal .form-control {
    height: 56px;
    border-top-left-radius: 30px;
    border-bottom-left-radius: 30px;
	padding-left:30px;
}
#payAmountModal .btn {
    border-top-right-radius: 30px;
    border-bottom-right-radius: 30px;
	padding-right:20px;
	background:linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
}
#payAmountModal .form-control:focus {
    color: #495057;
    background-color: #fff;
    outline: 0;
    box-shadow: none;
}
#payAmountModal .top-strip{
	height: 155px;
    background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
    transform: rotate(141deg);
    margin-top: -128px;
    margin-right: 190px;
    margin-left: -130px;
}
#payAmountModal .bottom-strip{
	height: 155px;
    background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
    transform: rotate(128deg);
    margin-top: -110px;
    margin-right: -250px;
    margin-left: 315px;
}
select.form-control.categories {
  border: 2px solid rgba(255, 255, 255, 0.6);
}
/**************************/


/****** extra *******/
#Reloadpage{
    cursor:pointer;
}
.error{
  color:#f00;
  display:none;
}
.detail{
  margin: 18px 0px;
  border-bottom: 1px solid #eaeaea;
  font-size: 14px;
  padding-bottom: 10px;
}
.content_section_payment {
    position: relative;
}
.content_section_payment img.upi {
    width: 30%;
    position: absolute;
    bottom: -20px;
    right: -10px;
    opacity: 0.1;
}
.content_section_payment img.paytm {
    position: absolute;
    width: 27%;
    right: -1px;
    bottom: -15px;
    opacity: 0.1;
}

/* User Transaction Modal End */
/* Ticket Section Css */
  .unread{
    background-color: #f6f9fc;
    font-weight: bold;
  }
  .unread h5,.unread small,.unread p{
    font-weight: bold;
  }
  .mesgs .msg_history img{ max-width:100%;}
.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: auto;
  max-width: 50%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 100%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 100%;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.incoming_msg{ overflow:hidden; margin:15px 0 15px;}
.outgoing_msg{ overflow:hidden; margin:15px 0 15px;}
.sent_msg {
  float: right;
  width: auto;
  max-width: 40%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 100%;
  overflow-y: auto;
  max-height: 40vh;
}
.user-image-list img{
  width:70px;
  height:70px;
  border-radius: 50%;
}
.ticketinfo{
  border:1px solid #333;
}
.incoming_msg_img img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}
h4.pmd-list-title {
    min-width: 115px;
}
span.content-text {
    width: 100%;
}
.h-35{
  height:35px;
}
.content-text a.fancybox-thumb img {
  width:80px;
  height:80px;
  padding: 4px;
  line-height: 1.42857143;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  object-fit: cover;
}
.image-with-comment .fancybox-thumb img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    margin-bottom: 4px;
}
.image-with-comment {
    max-width: 170px;
}
.image-with-comment .fancybox-thumb:nth-child(4){
  position:relative;
}
.image-with-comment .fancybox-thumb:nth-child(4) .overlay-count{
  position: absolute;
  top: -29px;
  left: 0px;
  padding: 28px 27px;
  background-color: #08080873;
  color: #fff;
}
.content-text .fancybox-thumb:nth-child(12){
  position:relative;
}
.content-text .fancybox-thumb:nth-child(12) .overlay-count{
  position: absolute;
  top: -22px;
  left: 5px;
  padding: 23px 26px;
  background-color: #08080873;
  color: #fff;
}

/* Ticket Section Css End */

/* Loader Css */
.loader-div {
  display:none;
  position: fixed;
  left: 0;
  z-index: 9;
  background: #413b835e;
  width: 100%;
  height: 100%;
  text-align: center;
}
.loader {
  max-width: 8rem;
  width: 100%;
  height: auto;
  stroke-linecap: round;
  margin-top: 20%;
}

circle {
  fill: none;
  stroke-width: 3.5;
  -webkit-animation-name: preloader;
          animation-name: preloader;
  -webkit-animation-duration: 3s;
          animation-duration: 3s;
  -webkit-animation-iteration-count: infinite;
          animation-iteration-count: infinite;
  -webkit-animation-timing-function: ease-in-out;
          animation-timing-function: ease-in-out;
  -webkit-transform-origin: 170px 170px;
          transform-origin: 170px 170px;
  will-change: transform;
}
circle:nth-of-type(1) {
  stroke-dasharray: 550;
}
circle:nth-of-type(2) {
  stroke-dasharray: 500;
}
circle:nth-of-type(3) {
  stroke-dasharray: 450;
}
circle:nth-of-type(4) {
  stroke-dasharray: 300;
}
circle:nth-of-type(1) {
  -webkit-animation-delay: -0.15s;
          animation-delay: -0.15s;
}
circle:nth-of-type(2) {
  -webkit-animation-delay: -0.3s;
          animation-delay: -0.3s;
}
circle:nth-of-type(3) {
  -webkit-animation-delay: -0.45s;
  -moz-animation-delay:  -0.45s;
          animation-delay: -0.45s;
}
circle:nth-of-type(4) {
  -webkit-animation-delay: -0.6s;
  -moz-animation-delay: -0.6s;
          animation-delay: -0.6s;
}

@-webkit-keyframes preloader {
  50% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}

@keyframes preloader {
  50% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}

/* Loader Css */
.back-profile img.card-img-top{
    max-height: 200px;
    object-fit: cover;
}
.cover-image {
    min-height: 200px;
    background-position: center;
}
.cover-image img{
  max-width: 200px;
  max-height: 200px;
  position: absolute;
  left: 40%;
  top: 35%;
  width: 200px;
  object-fit: contain;
  background:#fff;

}
.mt-10{
  margin-top:5rem;
}
#updateModal .modal-content{
	overflow:hidden;
}

#updateModal .btn {
	background:linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
}
#updateModal .form-control:focus {
    color: #495057;
    background-color: #fff;
    outline: 0;
    box-shadow: none;
}
#updateModal .top-strip{
	height: 155px;
    background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
    transform: rotate(141deg);
    margin-top: -128px;
    margin-right: 190px;
    margin-left: -130px;
}
#updateModal .bottom-strip{
	height: 155px;
    background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
    transform: rotate(128deg);
    margin-top: -110px;
    margin-right: -250px;
    margin-left: 315px;
}
.count_unread {
    position: absolute;
    right: 33px;
}
.excel_filediv {
  position: absolute;
  top: 0;
  right: 0;
}
.excel_filediv label{
  display: inline-block;
    width: 34px;
    height: 34px;
    margin-bottom: 0;
    background: #ffffff;
    border: 1px solid transparent;
    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
    cursor: pointer;
    font-weight: normal;
    transition: all 0.2s ease-in-out;
}
a.list-group-item.list-group-item-action.flex-column.align-items-start{
  z-index:0;
}
.font-15 {
    font-size: 13px;
    z-index:9999;
    position: absolute;
    display: block;
}
select.status {
    border-radius: 5px;
    padding: 2px 10px;
}
select.category {
    border-radius: 5px;
    padding: 2px 10px;
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
}
select.green {
    border-color: #5dce89;
    color: #5dce89;
}
select.red {
    border-color: #ed3b5b;
    color: #ed3b5b;
}
</style>