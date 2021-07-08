<?php

namespace App\Http\Controllers\Api\v1\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use File,URL,Mail,Hash,Exception;

use App\CommonHelpers;

use App\Models\Sellers;
use App\Models\SellerDevices;


class LoginController extends Controller
{

    /**
     * Login seller with phone_number and password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array 
     */
     public function login(Request $request){
        
        $todayDate=date("Y-m-d H:i:s");
        $expirtDateTime= date("Y-m-d H:i:s",strtotime("+5 minutes", strtotime($todayDate)));

        $input = $request->all();

        $messages = array(
            'phone_number.exists'=>'Mobile number isn\'t registered with us.'
        );
        
        $validator = Validator::make($input,[
            'phone_number'   => ['required',Rule::exists('sellers')->where(function ($query) {
                                            return $query->where('deleted_at', '=', null);
                                        })
                                ,'numeric'],
            'password'    => 'required',
            'device_token' => 'required',
            'device_type'  => 'required'
        ],$messages);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return  response()->json($ret); 
        }else{
                $seller = Sellers::where('phone_number', $input['phone_number'])
                                    ->where('deleted_at',null)
                                    ->first();
                if($seller){
                    if($seller->is_active=='1'){
                        //check credentials

                        if($seller->is_verified =='1'){

                            if( ! Hash::check( $input['password'], $seller->password) ){
                                $ret = array('success'=>0, 'message'=>'Phone number and Password Mismatch');
                                return  response()->json($ret);
                            }
                        
                            SellerDevices::updateOrCreate(["device_token" => $input['device_token']],['seller_id'=>$seller->id,'device_type'=>$input['device_type'], 'device_token' => $input['device_token']]);

                            $accessToken = $seller->createToken('authToken')->accessToken;
                            $message = array(
                                                'success'=>1,
                                                'accessToken'=>$accessToken,
                                                'data'=>$seller,
                                                'message'=>'Seller login successful.'
                                            );
                            return  response()->json($message,200,[],JSON_NUMERIC_CHECK);

                        }else{
                            $message = array(
                                                'success'=>0,
                                                'message'=>'Your account is yet to be verified by our team.',
                                            );

                            return  response()->json($message,200,[],JSON_NUMERIC_CHECK);
                        }

                    }else{
                        $ret = array('success'=>0, 'message'=>'Seller is inactive, please contact support');
                        return  response()->json($ret);
                    }
                }else{
                    $ret = array('success'=>0, 'message'=>'Mobile number isn\'t registered with us.');
                    return  response()->json($ret);
                }
        }
     }



    /**
     * Logout seller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array 
     */
     public function logout(Request $request)
     {

        $seller = Auth::guard('seller')->user();
        // dd($seller->toArray());

         $input = $request->all();
        
        $validator = Validator::make($input,[
            'device_type' => 'required',
            'device_token'  => 'required',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return  response()->json($ret); 
        }
        
        if( $seller ){
            try
            {
                SellerDevices::where('device_type',$input['device_type'])
                            ->where( 'device_token' , $input['device_token'] )
                            ->delete();

                auth()->user()->token()->revoke();
                $response = array('success'=>1, 'message'=>'Seller logged out');
                return  response()->json($response);
            }
            catch(Exception $e)
            {
                $response = array('success'=>0, 'message'=>$e->getMessage());
                return  response()->json($response);
            }
        }
        else{
            
            $response = array('success'=>0, 'message'=>'User not found.');
            return  response()->json($response,401);
        }
     }
}
