<?php

namespace App\Http\Controllers\Api\v1\Seller\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\CommonHelpers;
use Mail,Auth;

use App\Models\Sellers;
use App\Models\SellerDevices;

class ResetPasswordController extends Controller
{

    /**Send reset confirmation otp
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array 
     */
    public function sendResetOtp(Request $request)
    {

        $input = $request->all();

        $messages = [
            'phone_number.exists' => 'Phone number is not registered, please sign up',
        ];

        $validation = Validator::make($input, [
            'phone_number' => 'required|exists:sellers,phone_number',
        ],$messages);

        if ( $validation->fails() ) {

            $msg = array('success' => 0,'message'=>$validation->messages()->first());
            return response()->json($msg);

        }else{

            $seller = Sellers::where('phone_number',$input['phone_number'])->where('deleted_at',null)->first();
            
            if($seller){
                $todayDate=date("Y-m-d H:i:s");
                $expirtDateTime= date("Y-m-d H:i:s",strtotime("+5 minutes", strtotime($todayDate)));
                
                $seller->otp = CommonHelpers::generateOtp(6);
                $seller->otp_expiration_time=$expirtDateTime;
                $seller->save();
                
                $message = array(
                                 'success'=>1,
                                //  'data'=>$seller,
                                 'OTP'=>$seller->otp,
                                 'message'=>'Please check your phone number for OTP',
                                 'otp_expiry_time'=>$expirtDateTime
                                );
                return  response()->json($message,200,[],JSON_NUMERIC_CHECK);

             }else{

                $response = array('success'=>0, 'message'=>'No registered user found with provided phone number. Please signup.');
                return  response()->json($response);
            }

        }

        
    }

    /**Change password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array 
     */
    public function changePassword(Request $request)
    {       
        $input = $request->all();
        $messages = [
            'password.required' => 'Please enter a valid password',
            // 'seller_id' => 'required | exists:sellers,id'
            'phone_number' => 'required|exists:sellers,phone_number',
        ];

        $validation = Validator::make($input, [
            'password' => 'required|confirmed|min:6',
        ],$messages);

        if ( $validation->fails() ) {
            $msg = array('success' => 0,'message'=>$validation->messages()->first());
            return response()->json($msg);
        }else{
            
            $seller = Sellers::where('phone_number',$input['phone_number'])->where('deleted_at',null)->first();

            if( $seller ){
                try{
                    $seller->password = bcrypt($input['password']);
                    $seller->save();
                    $message = array(   'success'=>1,
                                        'message'=>'Password successfully changed.',
                                        // 'data'=>$seller
                                    );
                    return  response()->json($message);
                }
                catch( Exception $e){
                    report($e);
                    $message = array('success'=>0,'message'=>$e);
                    return  response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>'Seller account not found.');
                return  response()->json($message);
            }
        }
    }


    /**verify otp
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array 
     */
    public function verifyOtp(Request $request)
    {       
        $input = $request->all();
        $messages = [
            'otp.required' => 'The OTP is invalid.',
        ];

        $validation = Validator::make($input, [
            'otp'  => 'required',
            'phone_number' => 'required'
        ],$messages);

        if ( $validation->fails() ) {

            $msg = array('success' => 0,'message'=>$validation->messages()->first());
            return response()->json($msg);

        }else{
            
            $seller = Sellers::where('phone_number',$input['phone_number'])->where('deleted_at',null)->first();

            if( $seller ){
                if( $seller->otp == $input['otp'] ){
                    if( $seller->otp_expiration_time >=  date("Y-m-d H:i:s") ){
                        try{
                            $seller->otp = null;
                            $seller->otp_expiration_time = null;
                            $seller->save();
                            $message = array(
                                                'success'=>1,
                                                'message'=>'OTP verified successfully.',
                                                // 'data'=>$seller
                                            );
                            return  response()->json($message);
                        }
                        catch( Exception $e){
                            report($e);
                            $message = array('success'=>0,'message'=>$e);
                            return  response()->json($message);
                        }
                    }
                    else{
                        $message = array('success'=>0,'message'=>'The OTP has expired.');
                        return  response()->json($message);
                    }
                }
                else{
                    $message = array('success'=>0,'message'=>'The OTP is invalid.');
                    return  response()->json($message);
                }
            }
            else{
                
                $message = array('success'=>0,'message'=>'No account with phone number '.$input['phone_number'].' found. Please signup.');
                return  response()->json($message);
            }
        }
    }


    /**Change current password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array 
     */
    public function changeCurrentPassword(Request $request)
    {       
        $seller = Auth::guard('seller')->user();

        $input = $request->all();
        $messages = [
            'old_password.required' => 'Please enter a valid previous password',
            'new_password.required' => 'Please enter a valid new password',
        ];

        $validation = Validator::make($input, [
            'old_password'  => 'required',
            'new_password' => 'required',
            'confirm_new_password' => 'required|same:new_password'
        ],$messages);

        if ( $validation->fails() ) {
            $msg = array('success' => 0,'message'=>$validation->messages()->first());
            return response()->json($msg);
        }else{
            
            if( $seller ){
                try{
                    if( \Hash::check( $input['old_password'], $seller->password)){
                        $seller->password = bcrypt($input['new_password']);
                        if($seller->save())
                            $message = array('success'=>1,'message'=>'Password successfully changed.');
                        else
                            $message = array('success'=>0,'message'=>'Please try again.');

                        return  response()->json($message);                    
                    }else{
                        $message = array('success'=>0,'message'=>'Please enter a valid previous password');
                        return  response()->json($message);
                    }                   
                }
                catch( Exception $e){
                    report($e);
                    $message = array('success'=>0,'message'=>$e);
                    return  response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>'Seller account not found.');
                return  response()->json($message);
            }
        }
    }
}
