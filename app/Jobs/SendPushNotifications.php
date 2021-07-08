<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Support\Facades\Log;

class SendPushNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $data =[];

    public function __construct($content)
    {
        $this->data = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $offer_id       = $this->data['offer_id'];
        $category_id    = $this->data['category_id'];
        $description    = $this->data['description'];
        $image          = $this->data['icon'];
       
        $device_token=User::where('is_active',1)->where('OTP','=',"")->where('device_token','!=',"")->pluck('device_token')->toArray();
        //print'<pre>';print_R($users);exit;
        $push = new PushNotification('fcm');
        $push->setConfig([
            'priority' => 'high',
            'time_to_live' => 0
        ]); 
        $message='New Excited Offers Added';
        try {
            $push->setMessage([
                'data' => [
                    'title'       => $message,
                    'body'        => $message,
                    'sound'       => 'default',
                    'message'     => $description,
                    'offer_id'    => $offer_id,
                    'category_id' => $category_id,
                    'image'       => $image
                ]
            ]);

            $push->setApiKey(env('FCM_API_KEY'));               
            $push->setDevicesToken($device_token);
            $push->send();
            $result = $push->getFeedback();
            //Log::info(json_encode($result));
           // print_R($this->data);exit;           
        }catch (Exception $e) {
            Log::info("Not Sent");
        }  
    }
}
