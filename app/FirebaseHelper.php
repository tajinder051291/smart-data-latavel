<?php

namespace App;

use Illuminate\Http\Request;

use App\Models\User;
use Auth,Exception,DB;

use Edujugon\PushNotification\PushNotification;
use Illuminate\Support\Facades\Log;

class FirebaseHelper
{

    private $push;

    public function __construct( )
    {
        $this->push = new PushNotification('fcm');
        $this->push->setConfig([
            'priority' => 'high',
            'time_to_live' => 3
        ]);
        $this->push->setApiKey(env('FCM_API_KEY'));
    }

    public function notifyNewBidPlaced( $to, $order_id )
    {
      
        try {
                $user = \Auth::user();

                $title = "New bid placed";
                $body =  $user->name ." placed a new bid. Tap to see.";

                // $badge_count  = $this->getbadgeCount( $data->connected_user_id );
                // if( $badge_count == 0 )
                    $badge_count = 1;

                $notifyTo = $to->load('user_device_tokens');
                Log::info(json_encode($notifyTo));
                
                $this->push->setMessage([
                        'notification' => [
                                            'title'       => $title,
                                            'body'        => $body,
                                            'sound'       => 'default',
                                            // 'mutable-content'=> 1,
                                            'content-available'=> 1,
                                            'badge' => (int) $badge_count
                                        ],
                        'data' => [
                                'notification_type' =>'bid_placed',
                                'order_id'    =>(int) $order_id
                        ]
                    ]);
                $this->push->setDevicesToken($notifyTo->user_device_tokens()->pluck('device_token')->toArray());
                $this->push->send();

                //check for invalid devicetokens
                $unregistered = $this->push->getUnregisteredDeviceTokens();

                //remove invalid devicetokens
                // if( !empty($unregistered) ){
                    // $this->removeInvalidDeviceToken($unregistered);
                // }

                $result = $this->push->getFeedback();

                Log::info(json_encode($result));
        }
        catch( \Exception $e){
            Log::error($e->getMessage());
            dump($e->getMessage());
        }

    }

    public function notifyOrderCancelled( $to, $order_id )
    {
      
        try {
                $user = \Auth::user();

                $title = "An order is cancelled";
                $body =  $user->name ." cancelled an order. Tap to see.";

                // $badge_count  = $this->getbadgeCount( $data->connected_user_id );
                // if( $badge_count == 0 )
                    $badge_count = 1;

                $notifyTo = $to->load('user_device_tokens');

                $this->push->setMessage([
                        'notification' => [
                                            'title'       => $title,
                                            'body'        => $body,
                                            'sound'       => 'default',
                                            // 'mutable-content'=> 1,
                                            'content-available'=> 1,
                                            'badge' => (int) $badge_count
                                        ],
                        'data' => [
                                'notification_type' =>'order_cancelled',
                                'order_id'    =>(int) $order_id
                        ]
                    ]);
                $this->push->setDevicesToken($notifyTo->user_device_tokens()->pluck('device_token')->toArray());
                $this->push->send();

                //check for invalid devicetokens
                $unregistered = $this->push->getUnregisteredDeviceTokens();

                //remove invalid devicetokens
                // if( !empty($unregistered) ){
                //     $this->removeInvalidDeviceToken($unregistered);
                // }

                $result = $this->push->getFeedback();

                Log::info(json_encode($result));
        }
        catch( \Exception $e){
            Log::error($e->getMessage());
            dump($e->getMessage());
        }

    }

    public function notifyBidNegotiated( $to, $order_id )
    {
      
        try {
                $user = \Auth::user();

                $title = "A bid is negotiated.";
                $body =  $user->name ." negotiated a bid. Tap to see.";

                // $badge_count  = $this->getbadgeCount( $data->connected_user_id );
                // if( $badge_count == 0 )
                    $badge_count = 1;

                $notifyTo = $to->load('user_device_tokens');

                $this->push->setMessage([
                        'notification' => [
                                            'title'       => $title,
                                            'body'        => $body,
                                            'sound'       => 'default',
                                            // 'mutable-content'=> 1,
                                            'content-available'=> 1,
                                            'badge' => (int) $badge_count
                                        ],
                        'data' => [
                                'notification_type' =>'bid_negotiated',
                                'order_id'    =>(int) $order_id
                        ]
                    ]);
                $this->push->setDevicesToken($notifyTo->user_device_tokens()->pluck('device_token')->toArray());
                $this->push->send();

                //check for invalid devicetokens
                $unregistered = $this->push->getUnregisteredDeviceTokens();

                //remove invalid devicetokens
                // if( !empty($unregistered) ){
                //     $this->removeInvalidDeviceToken($unregistered);
                // }

                $result = $this->push->getFeedback();

                Log::info(json_encode($result));
        }
        catch( \Exception $e){
            Log::error($e->getMessage());
            dump($e->getMessage());
        }

    }

    public function notifyOrderAccepted( $to, $order_id )
    {
      
        try {
                $user = \Auth::user();

                $title = "Order accepted.";
                $body =  "Your order is accepted. Tap to see.";

                // $badge_count  = $this->getbadgeCount( $data->connected_user_id );
                // if( $badge_count == 0 )
                    $badge_count = 1;

                $notifyTo = $to->load('user_device_tokens');

                // dd($notifyTo->user_device_tokens->toArray());

                $this->push->setMessage([
                        'notification' => [
                                            'title'       => $title,
                                            'body'        => $body,
                                            'sound'       => 'default',
                                            // 'mutable-content'=> 1,
                                            'content-available'=> 1,
                                            'badge' => (int) $badge_count
                                        ],
                        'data' => [
                                'notification_type' =>'order_accepted',
                                'order_id'    =>(int) $order_id
                        ]
                    ]);
                $this->push->setDevicesToken($notifyTo->user_device_tokens()->pluck('device_token')->toArray());
                $this->push->send();

                //check for invalid devicetokens
                $unregistered = $this->push->getUnregisteredDeviceTokens();

                //remove invalid devicetokens
                // if( !empty($unregistered) ){
                //     $this->removeInvalidDeviceToken($unregistered);
                // }

                $result = $this->push->getFeedback();

                Log::info(json_encode($result));
        }
        catch( \Exception $e){
            Log::error($e->getMessage());
            dump($e->getMessage());
        }

    }

    public function notifyInvoiceCreated( $to, $order_id )
    {
      
        try {
                $user = \Auth::user();

                $title = "An invoice is created.";
                $body =  "Tap to see.";

                // $badge_count  = $this->getbadgeCount( $data->connected_user_id );
                // if( $badge_count == 0 )
                    $badge_count = 1;

                $notifyTo = $to->load('user_device_tokens');

                // dd($notifyTo->user_device_tokens->toArray());

                $this->push->setMessage([
                        'notification' => [
                                            'title'       => $title,
                                            'body'        => $body,
                                            'sound'       => 'default',
                                            // 'mutable-content'=> 1,
                                            'content-available'=> 1,
                                            'badge' => (int) $badge_count
                                        ],
                        'data' => [
                                'notification_type' =>'invoice_created',
                                'order_id'    =>(int) $order_id
                        ]
                    ]);
                $this->push->setDevicesToken($notifyTo->user_device_tokens()->pluck('device_token')->toArray());
                $this->push->send();

                //check for invalid devicetokens
                $unregistered = $this->push->getUnregisteredDeviceTokens();

                //remove invalid devicetokens
                // if( !empty($unregistered) ){
                //     $this->removeInvalidDeviceToken($unregistered);
                // }

                $result = $this->push->getFeedback();

                Log::info(json_encode($result));
        }
        catch( \Exception $e){
            Log::error($e->getMessage());
            dump($e->getMessage());
        }

    }

    public function notifyPickupAssigned( $to, $order_id )
    {
      
        try {
                $user = \Auth::user();

                $title = "Order pickup assigned.";
                $body = "Tap to see.";

                // $badge_count  = $this->getbadgeCount( $data->connected_user_id );
                // if( $badge_count == 0 )
                    $badge_count = 1;

                $notifyTo = $to->load('user_device_tokens');

                $this->push->setMessage([
                        'notification' => [
                                            'title'       => $title,
                                            'body'        => $body,
                                            'sound'       => 'default',
                                            // 'mutable-content'=> 1,
                                            'content-available'=> 1,
                                            'badge' => (int) $badge_count
                                        ],
                        'data' => [
                                'notification_type' =>'pickup_assigned',
                                'order_id'    =>(int) $order_id
                        ]
                    ]);
                $this->push->setDevicesToken($notifyTo->user_device_tokens()->pluck('device_token')->toArray());
                $this->push->send();

                //check for invalid devicetokens
                $unregistered = $this->push->getUnregisteredDeviceTokens();

                //remove invalid devicetokens
                // if( !empty($unregistered) ){
                //     $this->removeInvalidDeviceToken($unregistered);
                // }

                $result = $this->push->getFeedback();

                Log::info(json_encode($result));
        }
        catch( \Exception $e){
            Log::error($e->getMessage());
            dump($e->getMessage());
        }

    }
    
    public function notifyPickupConfirmed( $to, $order_id )
    {
      
        try {
                $user = \Auth::user();

                $title = "Order pickup confirmed.";
                $body = "Tap to see.";

                // $badge_count  = $this->getbadgeCount( $data->connected_user_id );
                // if( $badge_count == 0 )
                    $badge_count = 1;

                $notifyTo = $to->load('user_device_tokens');

                $this->push->setMessage([
                        'notification' => [
                                            'title'       => $title,
                                            'body'        => $body,
                                            'sound'       => 'default',
                                            // 'mutable-content'=> 1,
                                            'content-available'=> 1,
                                            'badge' => (int) $badge_count
                                        ],
                        'data' => [
                                'notification_type' =>'pickup_confirmed',
                                'order_id'    =>(int) $order_id
                        ]
                    ]);
                $this->push->setDevicesToken($notifyTo->user_device_tokens()->pluck('device_token')->toArray());
                $this->push->send();

                //check for invalid devicetokens
                $unregistered = $this->push->getUnregisteredDeviceTokens();

                //remove invalid devicetokens
                // if( !empty($unregistered) ){
                //     $this->removeInvalidDeviceToken($unregistered);
                // }

                $result = $this->push->getFeedback();

                Log::info(json_encode($result));
        }
        catch( \Exception $e){
            Log::error($e->getMessage());
            dump($e->getMessage());
        }

    }


    public function notifyStockDeposited( $to, $order_id )
    {
      
        try {
                $user = \Auth::user();

                $title = "Stock deposited.";
                $body = "Tap to see.";

                // $badge_count  = $this->getbadgeCount( $data->connected_user_id );
                // if( $badge_count == 0 )
                    $badge_count = 1;

                $notifyTo = $to->load('user_device_tokens');

                $this->push->setMessage([
                        'notification' => [
                                            'title'       => $title,
                                            'body'        => $body,
                                            'sound'       => 'default',
                                            // 'mutable-content'=> 1,
                                            'content-available'=> 1,
                                            'badge' => (int) $badge_count
                                        ],
                        'data' => [
                                'notification_type' =>'stock_deposited',
                                'order_id'    =>(int) $order_id
                        ]
                    ]);
                $this->push->setDevicesToken($notifyTo->user_device_tokens()->pluck('device_token')->toArray());
                $this->push->send();

                //check for invalid devicetokens
                $unregistered = $this->push->getUnregisteredDeviceTokens();

                //remove invalid devicetokens
                // if( !empty($unregistered) ){
                //     $this->removeInvalidDeviceToken($unregistered);
                // }

                $result = $this->push->getFeedback();

                Log::info(json_encode($result));
        }
        catch( \Exception $e){
            Log::error($e->getMessage());
            dump($e->getMessage());
        }

    }

    


    public function notifyStockOnHold( $to, $order_id )
    {
      
        try {
                $user = \Auth::user();

                $title = "Stock put on hold.";
                $body = "Tap to see.";

                // $badge_count  = $this->getbadgeCount( $data->connected_user_id );
                // if( $badge_count == 0 )
                    $badge_count = 1;

                $notifyTo = $to->load('user_device_tokens');

                $this->push->setMessage([
                        'notification' => [
                                            'title'       => $title,
                                            'body'        => $body,
                                            'sound'       => 'default',
                                            // 'mutable-content'=> 1,
                                            'content-available'=> 1,
                                            'badge' => (int) $badge_count
                                        ],
                        'data' => [
                                'notification_type' =>'on_hold',
                                'order_id'    =>(int) $order_id
                        ]
                    ]);
                $this->push->setDevicesToken($notifyTo->user_device_tokens()->pluck('device_token')->toArray());
                $this->push->send();

                //check for invalid devicetokens
                $unregistered = $this->push->getUnregisteredDeviceTokens();

                //remove invalid devicetokens
                // if( !empty($unregistered) ){
                //     $this->removeInvalidDeviceToken($unregistered);
                // }

                $result = $this->push->getFeedback();

                Log::info(json_encode($result));
        }
        catch( \Exception $e){
            Log::error($e->getMessage());
            dump($e->getMessage());
        }

    }

    




    public function removeInvalidDeviceToken( $tokens )
    {
        try{
            UserDevices::whereIn('device_token', $tokens)->delete();
        }
        catch( \Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
    

    private function getbadgeCount( $user_id )
    {   
        try
        {
            $pending = Connects::where('status','=',1)
                                    ->where('receiver_id','=',$user_id)
                                     ->reject(function ($connect) {
                                            return $connect->is_blocked || $connect->is_reported || !$connect->is_active;
                                         })
                                    ->count()
                                    ;

            $unreadChats = UsersChats::where('receiver_id',$user_id)
                                        ->where('is_read',0)
                                        ->get()
                                        ->reject(function ($chat) {
                                            return ($chat->project_count == 0 && $chat->connection_status == 0) //no project and no connection
                                                    || $chat->sender_blocked  //user is blocked
                                                    || $chat->sender_reported  //user is reported
                                                    ;
                                         })
                                        ->count()
                                        ;
            
             if( \DB::table('project_list_viewed_time')->where('user_id',$user_id)->exists() ){
                    $projects = Projects::without('other_user_data','chat_data','milestones','events')
                                ->leftJoin('project_list_viewed_time','projects.connected_user_id', '=', 'project_list_viewed_time.user_id')
                                ->where( 'projects.connected_user_id', $user_id )
                                ->where( 'project_list_viewed_time.user_id', $user_id)
                                ->whereRaw('project_list_viewed_time.last_seen_time <= projects.created_at')
                                ->select('projects.id')
                                ->count()
                                ;
            }else{
                    $projects = Projects::without('other_user_data','chat_data','milestones','events')
                                        ->where( 'projects.connected_user_id', $user_id )
                                        ->select('projects.id')
                                        ->count()
                                        ;
            }

           return (int) ($pending + $projects + $unreadChats);
        }
        catch( Exception $e)
        {
            Log::error($e->getMessage());
            return $e->getMessage();
        }       

    }

}
