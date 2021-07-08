<?php

namespace App\Http\Controllers\Api\v1\User;

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


use Carbon\Carbon;
use Auth,File,URL;

class MobilesController extends Controller
{

    public function list(Request $request)
    {

        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'limit' => 'numeric | nullable',
            'page' => 'numeric | nullable',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }
        
        if($user){
            try{
                $limit = $request->limit ? $request->limit : 10;

                $brandAndModels = MobileBrands::has('models')
                                              ->with('models')
                                              ->where('mobile_brands.is_active',1)
                                              ->orderBy('brand_name','asc')
                                              ->paginate($limit);
                                            //   dd($brandAndModels->toArray()['data']);
                $brandAndModels = $brandAndModels->toArray();
                return response()->json(array('success'=>1,'data'=>$brandAndModels['data'],'current_page'=>$brandAndModels['current_page'],'last_page'=>$brandAndModels['last_page'],'total_results'=>$brandAndModels['total'],'message'=>"Brand And Model listing") ,200,[],JSON_NUMERIC_CHECK);

            }catch(Exception $e){
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Invalid user");
            return response()->json($message);
        }
    }
    
    /**
     * Manage requirement
     * 
     * @return \Illuminate\Http\Response
     * */
    public function manageRequirement( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'brand_id' => ['required',Rule::exists('mobile_brands','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user){
            try
            {
                if($input[ 'active_model_ids' ]!=""){
                    $model_ids = explode(',',$input[ 'active_model_ids' ]);
                    $all_models = MobileModels::where('brand_id',$input['brand_id'])->get()->toArray();

                    $active = MobileModels::where('brand_id', $input['brand_id'])->whereIn('id',$model_ids)->update(['is_active'=>1]);
                    $inactive = MobileModels::where('brand_id', $input['brand_id'])->whereNotIn('id',$model_ids)->update(['is_active'=>0]);
                }else{
                    $inactive = MobileModels::where('brand_id', $input['brand_id'])->update(['is_active'=>0]);
                }

                $message = array('success'=>1,'message'=>"Requirements updated.");
                return response()->json($message);
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


     /**
     * Get all mobile models list
     * 
     * @return \Illuminate\Http\Response
     * */

     public function listMobileModels(Request $request)
     {
        $user = Auth::guard('user')->user();
        $input = $request->all();
       
        if( $user ){
            $list = MobileModels::where(['is_active'=>1])->get();
            
            $new = [];
            foreach( $list->toArray() as $key=>$item ){
                $exists = array_search($item['model'], array_column($new, 'model_name'));
                if($exists){
                    array_push($new[$exists]['model_id'], $item['id']);
                }else{
                    array_push($new, ['model_name'=>$item['model'],'model_id'=>[$item['id']]]);
                }
            }

            $message = array('success'=>1,'message'=>'Models listed successfully.','data'=>$new);
                    return response()->json($message);
        }else{
            $message = array('success'=>0,'message'=>"Not authorized.");
            return response()->json($message);
        }
     }

}
