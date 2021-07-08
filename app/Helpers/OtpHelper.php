<?php
namespace App\Helpers;

use DB;

class OtpHelper
{
    public static function sendOtp($otp,$phonenumber,$message,$type='0'){
    	//$message='<#> Your Woonga OTP is: '.$otp.' Note: Please DO NOT SHARE this OTP with anyone. kWoZJ19rzPx';
    	if($type=='0'){
    		$message="Use ".$otp." as your login OTP.OTP is confidential.Woonga never calls you asking for OTP.";
    	}else{
    		$message="Use ".$otp." to verify your Phone Number.Woonga never calls you asking for OTP.";
    	}
    	
		$key="309404AygNz8It4K5dfdf27eP1";
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://control.msg91.com/api/sendotp.php?template=&otp_length=&authkey=$key&message=$message&sender=WOONGA&mobile=$phonenumber&otp=$otp&otp_expiry=1",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "",
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
		  return false;
		} else {
		  return true;
		}
    }
}