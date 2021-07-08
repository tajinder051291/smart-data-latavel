<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Sellers;
use Aws\S3\Exception\S3Exception;
use Auth,File,URL,Exception,Log,Mail;
use Mailgun\Mailgun;

use App\FirebaseHelper;

class CommonHelpers
{
    /**
     * Method to generate random OTP
     *
     * @param string $length OTP length
     *
     * @return string Returns OTP
     */
    public static function generateOtp($length = 4) 
    {
        $characters = '123456789';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }

    public static function randomString($n) { 
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $randomString = ''; 
      
        for ($i = 0; $i < $n; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
      
        return $randomString; 
    } 


     /**
     * Method to delete image from s3
     *
     * @param string $path S3 path of image
     *
     * @return string Returns delete status
     */
    public static function deleteImageFromS3( $folder,$path )
    {
      $deleteStatus = 0;
      try{
          if( is_array($path ) ){
            foreach( $path as $k=>$p){
                //extract image name from file url
                $parsePath = parse_url($p);
                $pathArray = explode('/',$parsePath['path']);
                $index = count($pathArray) -1;
                $fileName = $pathArray[$index];
                if( $folder != ""){
                    $fileName = $folder .'/'. $fileName;
                }

                //check if file exists
                $fileExists = Storage::disk('s3')->exists($fileName);
                if( $fileExists ){
                    //try to delete file
                    $deleteStatus = Storage::disk('s3')->delete($fileName);
                }
            }
          }
          else{
                //extract image name from file url
                $parsePath = parse_url($path);
                $pathArray = explode('/',$parsePath['path']);
                $index = count($pathArray) -1;
                $fileName = $pathArray[$index];
                if( $folder != ""){
                    $fileName = $folder .'/'. $fileName;
                }

                //check if file exists
                $fileExists = Storage::disk('s3')->exists($fileName);
                if( $fileExists ){
                    //try to delete file
                    $deleteStatus = Storage::disk('s3')->delete($fileName);
                    if( $deleteStatus )
                        return array('msg'=>"File deleted successfully.",'status'=>1);
                    else
                        return array('msg'=>"Please try again.",'status'=>0);
                }else{
                    return array('msg'=>"File does not exists.",'status'=>0);
                }
          }
            
        }
        catch(Exception $e){
            Log::error($e);
            return array('status'=>0,'msg'=>$e->getMessage());
        }     
        
        
    }


    /**
     *Upload a image to s3
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array 
     */
    public static function uploadImageToS3($folder,$file)
    {
        try{
                $file = $file;
                $fileext    = $file->getClientOriginalExtension();
                $name = uniqid().'-'.time().'.'.$fileext;
                $filePath = $folder .'/'. $name;  
                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                // $image_path_for_db = "https://". config('filesystems.disks.s3.bucket') .".s3.". config('filesystems.disks.s3.region') .".amazonaws.com/".$filePath;
                $image_path_for_db = "https://". env("AWS_BUCKET") .".s3.". env("AWS_DEFAULT_REGION") .".amazonaws.com/".$filePath;
                return $image_path_for_db;
        }
        catch(Exception $e){
            Log::error($e);
            return $e;
        }     
        
    }

     /**
     * Get users by role
     * @param array $role Manager role type, default is 1 for Fortbell user
     * @param boolean $onlyEmail fetch only user email, default is false
     * @return \Illuminate\Http\Response
     */
    public static function getAllActiveUsersByRole( $role = [1], $onlyEmail = false ) 
    {        
        try
        {
           $users =  User::whereIn('user_role' ,$role)
                         ->where('is_active' , 1)                        
                         ->get();
            if( $onlyEmail ){
                $users = $users->pluck('email');
            }
            $message = array('success'=>1,'message'=>$users);
            return $message;
        }
        catch(Exception $e)
        {
            $message = array('success'=>0,'message'=>$e->getMessage());
            return $message;
        }        
    }

     /**
     * Send mail by role
     *
     * @param  array $data
     * @param  string push notification type
     * @param  array sms type
     * @return \Illuminate\Http\Response
     */
    public static function sendMailByRole( $data, $pushNotificationType = '', $smsNotificationType = '' ) 
    {
        try
        {
            //Send mail notification
            $allActiveUsers = self::getAllActiveUsersByRole($data['role'], false);
            if( $allActiveUsers['success'] == 1 ){
                foreach ($allActiveUsers['message'] as $recipient) {

                    Mail::send($data['template'], [ 'user' => $recipient->toArray() ,'data'=>$data['template_data'],'msg'=>$data['message'] ],
                        function ($m) use ($recipient, $data){
                        $m->from(config('mail.from.address'),config('app.name'));
                        $m->to('rajesh.softradix@gmail.com')->subject($data['subject']);
                        // $m->to($recipient->email)->subject('Bid placed');
                    });

                    if( $recipient->app_notifications ){
                        try{
                            self::sendPushNotification( $recipient, $data['order_id'], $data['type']  );
                        }
                        catch(\Exception $e){
                            // Log error
                            \Log::error($e->getMessage());
                        }
                    }


                    //break; //remove this for production
               
               
               
                }
            }else{
                \Log::error($allActiveUsers['message']);
            }
        }
        catch(Exception $e)
        {
            \Log::error($allActiveUsers['message']);
        }        
    }



     /**
     * Send mail to a user
     * 
     * @param  array $data
     * @return \Illuminate\Http\Response
     */
    public static function sendMailToUser( $data )
    {
        try
        {
            $user = User::where('id' , $data['user_id'])
                                // ->where('is_active' , 1)
                                ->first();
                                // dd($seller->toArray());
            if( $user ){
                //Send mail notification
                Mail::send($data['template'], [ 'user' => $user->toArray() ,'data'=>$data['template_data'],'msg'=>$data['message'] ],
                    function ($m) use ($user, $data){
                    $m->from(config('mail.from.address'),config('app.name'));
                    $m->to('rajesh.softradix@gmail.com')->subject($data['subject']);
                    // $m->to($user->email)->subject('Bid placed');
                });

                if( $user->app_notifications ){
                    try{
                        //Send push notification
                        self::sendPushNotification( $user, $data['order_id'], $data['type']  );

                    }
                    catch(\Exception $e){
                        // Get error here
                        \Log::error($e->getMessage());
                    }
                }

            }else{
                \Log::error($user);
            }
        }
        catch(Exception $e)
        {
            \Log::error($user);
        }
    }


     /**
     * Send mail to seller
     * 
     * @param  array $data
     * @return \Illuminate\Http\Response
     */
    public static function sendMailToSeller( $data ) 
    {
        try
        {
            $seller = Sellers::where('id' , $data['seller_id'])
                                ->where('is_active' , 1)
                                ->first();
                                // dd($seller->toArray());
            if( $seller ){

                //Send mail notification
                Mail::send($data['template'], [ 'user' => $seller->toArray() ,'data'=>$data['template_data'],'msg'=>$data['message'] ],
                    function ($m) use ($seller, $data){
                    $m->from(config('mail.from.address'),config('app.name'));
                    $m->to('rajesh.softradix@gmail.com')->subject($data['subject']);
                    // $m->to($seller->email)->subject('Bid placed');
                });

                if( $seller->app_notifications ){
                    try{
                       //Send push notification
                    //    dd($seller,$data);
                       self::sendPushNotification( $seller, $data['order_id'], $data['type']  );

                    }
                    catch(\Exception $e){
                        // Get error here
                        \Log::error($e->getMessage());
                    }
                }

            }else{
                \Log::error($seller);
            }
        }
        catch(Exception $e)
        {
            \Log::error($seller);
        }
    }


    /**
     * Send push notification
     * 
     * @param  array $data
     * @param  string $type notification type
     * @return \Illuminate\Http\Response
     */
    public static function sendPushNotification( $recipient, $order_id, $type )
    {
        $firebase = new FirebaseHelper();
        
        switch ( trim($type) ) {

            case 'bid_placed':
                $firebase->notifyNewBidPlaced( $recipient, $order_id );
                break;

            case 'order_cancelled':
                $firebase->notifyOrderCancelled( $recipient, $order_id );
                break;

            case 'bid_negotiated':
                $firebase->notifyBidNegotiated( $recipient, $order_id );
                break;

            case 'order_accepted':
                $firebase->notifyOrderAccepted( $recipient, $order_id );
                break;

            case 'invoice_created':
                $firebase->notifyInvoiceCreated( $recipient, $order_id );
                break;

            case 'pickup_assigned':
                $firebase->notifyPickupAssigned( $recipient, $order_id );
                break;

            case 'pickup_confirmed':
                $firebase->notifyPickupConfirmed( $recipient, $order_id );
                break;

            case 'stock_deposited':
                $firebase->notifyStockDeposited( $recipient, $order_id );
                break;

            case 'on_hold':
                $firebase->notifyStockOnHold( $recipient, $order_id );
                break;
        }


    }

}
