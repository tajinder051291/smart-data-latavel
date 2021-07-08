<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\MobileBrands;
use App\Models\MobileModels;

use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\OrderAttachments;
use App\Models\Faq;

use App\CommonHelpers;

use Carbon\Carbon;
use Auth,File,URL;

class CommmonController extends Controller
{
     /**
     * Delete attachment
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAttachment(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();
        $validator = Validator::make($input,[
            'attachment_url' => "required | url"
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user){
            try
            {
                $deleted = CommonHelpers::deleteImageFromS3( 'bid',$input['attachment_url'] );
                if( $deleted['status'] ){
                    $message = array('success'=>$deleted['status'],'message'=>"Attachment deleted successfully.");
                    return response()->json($message);
                }else{
                    $message = array('success'=>$deleted['status'],'message'=>$deleted['msg']);
                    return response()->json($message);
                }
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
         }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }
}
