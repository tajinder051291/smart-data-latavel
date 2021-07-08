<?php

namespace App\Http\Controllers\Api\v1\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use App\Models\Sellers;
use App\Models\SellerDevices;

use App\CommonHelpers;



class RegisterController extends Controller
{
    /**
     * Register seller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array 
     */
    public function register(Request $request)
    {
        $todayDate=date("Y-m-d H:i:s");
        $expirtDateTime= date("Y-m-d H:i:s",strtotime("+5 minutes", strtotime($todayDate)));

        $input = $request->all();
        // dd($input);

        $messages = [
            'name.required' => 'Please enter a full valid URL',
            'email.required' => 'Please enter a full valid email',
            'email.unique' => 'Email is already registered. Please sign in.',
            'password.required' => 'Please enter a valid password',
            'tnc.required' => 'Please accept the terms and conditions.',
            'tnc.accepted' => 'Please accept the terms and conditions.',
            'country_code.required' => 'Please enter a valid country code.',
            'phone_number.required' => 'Please enter a valid phone number.',
            'phone_number.unique' => 'Phone number is already registered.',
            'aadhaar_number.size' => 'Aadhaar number must be of 12 digits.',
        ];
        
        $validator = Validator::make($input,[
            'name'     => 'required|max:55',
            'email'    => ['required',Rule::unique('sellers')->where(function ($query) {
                                                return $query->where('deleted_at', '=', null);
                                            }), 'email'],
            // 'password' => 'required|confirmed|min:6',
            'device_type'=>'required | integer',
            'device_token'=>'required | string',
            'tnc' => 'required|accepted',
            // 'country_code'  => 'required | number',
            'phone_number'   => ['required',Rule::unique('sellers')->where(function ($query) {
                                                return $query->where('deleted_at', '=', null);
                                            })
                        ,'numeric'],
                        
            'aadhaar_number' => "required | digits:12",
            'aadhaar_front_image' => "required | url",
            'aadhaar_back_image' => "required | url",

            'pan_number' => "required | alpha_num | size:10",
            'pan_image' => "required | url",

            'gst_number' => "required | alpha_num",
            'gst_image' => "required | url",

            'cheque_number' => "required | alpha_num",
            'cheque_image' => "required | url",

            'address' => "required | string",
            'pincode' => "required | integer",
            
        ],$messages);

        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return  response()->json($ret);
        }else{

            $seller = Sellers::where('email',$input['email'])->where('phone_number',$input['phone_number'])->where('deleted_at',null)->first();

            if(!$seller){

                // $input['password'] = bcrypt($input['password']);
                // $input['OTP'] = CommonHelpers::generateOtp(6);
                // $input['otp_expiration_time']=$expirtDateTime;

                try{
                    $seller = Sellers::create($input);
                    $accessToken = $seller->createToken('authToken')->accessToken;
                    
                    SellerDevices::create(['seller_id'=>$seller->id,'device_type'=>$input['device_type'], 'device_token' => $input['device_token']]);

                    $message = array(
                                        'success'=>1,
                                        // 'OTP'=>$input['OTP'],
                                        // 'accessToken'=>$accessToken,
                                        // 'data'=>Sellers::find($seller->id),
                                        'message'=>'Thank you. We have received your request. Our team will review your request and will process the same.'
                                    );
                    return  response()->json( $message,200,[],JSON_NUMERIC_CHECK );
                }
                catch(Exception $e){
                    report($e);
                    return response()->json(array('success' => 0, 'message' => $e->getMessage()));
                }

            }else{
                
                $message = array('success'=>0,'message'=>'Email and Phone number already exist');
                return  response()->json( $message );
            }
        }
    }


     /**
     * UPload file
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function uploadFile(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input,[
            'file'  => 'required | file',
            'type' => 'required | string',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        try{

            // $fileUrl = "https://dummy.url";
            $fileUrl = \App\CommonHelpers::uploadImageToS3( $input['type'] ,$input['file']);
            if( gettype($fileUrl) == "object" ){
                $message = array('success'=>0,'message'=>"File was not uploaded. Please try again.",'error'=>$fileUrl->getMessage());
                return response()->json($message);
            }

            return response()->json(array('success'=>1,'data'=>$fileUrl,'message'=>"File saved successfully."),200,[],JSON_NUMERIC_CHECK);
        }
        catch(Exception $e)
        {
        $message = array('success'=>0,'message'=>$e->getMessage());
        return response()->json($message);
        }
    }
}
