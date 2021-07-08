<?php

namespace App\Http\Controllers\Api\v1\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;


use App\Models\MobileBrands;
use App\Models\MobileModels;

use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\OrderAttachments;
use App\Models\Faq;

use App\CommonHelpers;

use Carbon\Carbon;
use Auth,File,URL;

class MobilesController extends Controller
{

    /**
     * List all brands with mobiles
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'limit' => 'numeric | nullable',
            'page' => 'numeric | nullable',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if($seller){
            try
            {
                $limit = $request->limit ? $request->limit : 10;

                $mobiles = MobileBrands::has('models')
                                        ->with('models')
                                        ->where('mobile_brands.is_active',1)
                                        ->orderBy('brand_name','asc')
                                        ->paginate($limit);

                // dd($mobiles->toArray());
                $mobiles = $mobiles->toArray();

                return response()->json(array('success'=>1,'data'=>$mobiles['data'],'current_page'=>$mobiles['current_page'],'last_page'=>$mobiles['last_page'],'total_results'=>$mobiles['total'],'message'=>"Mobiles listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }



    /**
     * Get model types for placed bid
     *
     * @return \Illuminate\Http\Response
     */
    public function getModelTypes(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'models' => "required | array"
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {
                //get all selected model types
                foreach( $input['models'] as $key=>$item ){
                    $types[$key]['brand'] = MobileBrands::where('id',$item['brand_id'])->pluck('brand_name')[0];
                    $types[$key]['brand_model'] = $item['model_name'];
                    $types[$key]['models'] = MobileModels::where('brand_id',$item['brand_id'])->where('model',$item['model_name'])->get();
                }

               return response()->json(array('success'=>1,'data'=>$types,'message'=>"Model types listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }



    /**
     * Place bid
     *
     * @return \Illuminate\Http\Response
     */
    public function placeBidBackup(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        // dd($input);

        $validator = Validator::make($input,[
            'order_items' => "required | array",
            'order_attachments' => "array",
            'delivery_method' => 'required',
            'delivery_date' => 'required | numeric',
            'stock_availablity' => 'required',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {
                //create order
                $order = Orders::create(['seller_id'=>$seller->id,'delivery_method'=>$input['delivery_method'],'due_date'=>Carbon::createFromTimestamp($input['delivery_date'])->toDateTimeString(),'stock_availablity'=>$input['stock_availablity']]);
                
                //dd($order);
                $order_items = $input['order_items'];

                foreach( $order_items as $key=>$item ){
                    $order_items[$key]['order_id'] = $order->id;
                    $order_items[$key]['created_at'] = Carbon::now();
                    $order_items[$key]['updated_at'] = Carbon::now();
                }

                // dd($order_items);

                $order_items = OrderItems::insert($order_items);

                if( isset( $input['order_attachments'] ) && count($input['order_attachments']) > 0 ){
                    $order_attachments = [];

                    foreach( $input['order_attachments'] as $key=>$item ){
                        $order_attachments[$key]['order_id'] = $order->id;
                        $order_attachments[$key]['image'] = $item;
                        $order_attachments[$key]['created_at'] = Carbon::now();
                        $order_attachments[$key]['updated_at'] = Carbon::now();
                    }

                    $order_attachments = OrderAttachments::insert($order_attachments);
                }

                // dd($order_attachments);

                $order->save();
                

               return response()->json(array('success'=>1,'data'=>Orders::with(['items','attachments'])->find($order->id),'message'=>"Order placed successfully.") ,200,[],JSON_NUMERIC_CHECK);
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }


    /**
     * Place bid
     *
     * @return \Illuminate\Http\Response
     */
    public function placeBid(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        // dd($input);

        $validator = Validator::make($input,[
            'order_items' => "required | array"
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {
                //dd($order);
                $order_items = $input['order_items'];

                $orders = [];

                foreach( $order_items as $key=>$item ){

                    //create unique order for an order item
                    $order = Orders::create(['seller_id'=>$seller->id,'delivery_method'=>$item['delivery_method'],'due_date'=>Carbon::createFromTimestamp($item['delivery_date'])->toDateTimeString(),'stock_availablity'=>$item['stock_availablity']]);

                    $item['order_id'] = $order->id;
                    $item['created_at'] = Carbon::now();
                    $item['updated_at'] = Carbon::now();

                    $order_item = OrderItems::create($item);

                    if( isset( $item['order_attachments'] ) && count($item['order_attachments']) > 0 ){
                        $order_attachments = [];
                        foreach( $item['order_attachments'] as $key=>$item ){
                            $order_attachments[$key]['order_id'] = $order->id;
                            $order_attachments[$key]['image'] = $item;
                            $order_attachments[$key]['created_at'] = Carbon::now();
                            $order_attachments[$key]['updated_at'] = Carbon::now();
                        }
                        $order_attachments = OrderAttachments::insert($order_attachments);
                    }
                    $order->save();

                    array_push($orders,$order->id);
                }                
                // dd($orders);

                $order = Orders::with(['items','attachments'])->whereIn('id',$orders)->get();
                // dd($order->count());
                //send email notification to Fortbell users               
                $mailData = array(
                            'template' => "emails.bidplaced",
                            'message' => "A new bid is placed.",
                            'subject' => "A new bid is placed.",
                            'role' => [1],
                            'template_data' => $order,
                            'order_id' => $order[$order->count() -1 ]->id,
                            'type' => 'bid_placed'
                        );
                        
                CommonHelpers::sendMailByRole( $mailData, 'bid_placed' );

                return response()->json(array('success'=>1,'data'=>$order,'message'=>"Order placed successfully.") ,200,[],JSON_NUMERIC_CHECK);
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }


    /**
     * Edit bid
     *
     * @return \Illuminate\Http\Response
     */
    public function editBidBackup(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        // dd($input);

        $validator = Validator::make($input,[
            'order_items' => "required | array",
            'order_attachments' => "array",
            'delivery_method' => 'required',
            'stock_availablity' => 'required',
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            $order = Orders::where('id',$input['order_id'])->where('seller_id',$seller->id);
            $attachments = OrderAttachments::where('order_id',$input['order_id'])->pluck('image')->toArray();

            if( $order->exists() ){
                try
                {
                    //update order
                    $order->update(['delivery_method'=>$input['delivery_method'],'stock_availablity'=>$input['stock_availablity']]);
                    
                    //update items
                    $order_items = $input['order_items'];
                    foreach($order_items as $k=>$item){
                        OrderItems::where('id',$item['id'])
                                  ->where('order_id',$item['order_id'])
                                  ->update( ['quantity' => $item['quantity'], 'price'=>$item['price'],'is_made_in_india' => $item['is_made_in_india'],'is_item_active' => $item['is_item_active'] ] );
                    }
                    
                    // add/delete attachments
                    if( isset( $input['order_attachments'] ) && count($input['order_attachments']) > 0 ){

                        $order_attachments = [];
                        
                        //if image url does not exist then add
                        foreach( $input['order_attachments'] as $key=>$item ){
                            if( in_array( $item, $attachments ) == false ){
                                $order_attachments[$key]['order_id'] = $input['order_id'];
                                $order_attachments[$key]['image'] = $item;
                                $order_attachments[$key]['created_at'] = Carbon::now();
                                $order_attachments[$key]['updated_at'] = Carbon::now();
                            }
                        }
                        $order_attachments = OrderAttachments::insert($order_attachments);
                        
                        //delete those attachments which are not in supplied attachment array
                        foreach( $attachments as $key=>$item ){
                            if( in_array( $item, $input['order_attachments'] ) == false ){
                                OrderAttachments::where('order_id',$input['order_id'])->where('image',$item)->delete();
                            }
                        }
                    }

                    return response()->json(array('success'=>1,'data'=>Orders::with(['items','attachments'])->find($input['order_id']),'message'=>"Bid updated successfully.") ,200,[],JSON_NUMERIC_CHECK);
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>"Order not found.");
                return response()->json($message);
            }
            
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }

    /**
     * Edit bid
     *
     * @return \Illuminate\Http\Response
     */
    public function editBid(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        // dd($input);

        $validator = Validator::make($input,[
            'order_items' => "required | array",
            'order_attachments' => "array",
            'delivery_method' => 'required',
            'stock_availablity' => 'required',
            'delivery_date' => 'required',
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            $order = Orders::where('id',$input['order_id'])->where('seller_id',$seller->id);
            $attachments = OrderAttachments::where('order_id',$input['order_id'])->pluck('image')->toArray();

            if( $order->exists() ){
                try
                {
                    //update order
                    $order->update([
                                    'delivery_method'=>$input['delivery_method'],
                                    'stock_availablity'=>$input['stock_availablity'],
                                    'due_date'=>Carbon::createFromTimestamp($input['delivery_date'])->toDateTimeString()
                                ]);
                    
                    //update items
                    $order_items = $input['order_items'];
                    foreach($order_items as $k=>$item){
                        OrderItems::where('id',$item['id'])
                                  ->where('order_id',$item['order_id'])
                                  ->update( [
                                                'quantity' => $item['quantity'], 
                                                'price'=>$item['price'],
                                                'is_made_in_india' => $item['is_made_in_india'],
                                                'is_item_active' => $item['is_item_active'],
                                                'brand_id' => $item['brand_id'],
                                                'model_id' => $item['model_id'],
                                        ]);
                    }
                    
                    // add/delete attachments
                    if( isset( $input['order_attachments'] ) && count($input['order_attachments']) > 0 ){

                        $order_attachments = [];
                        
                        //if image url does not exist then add
                        foreach( $input['order_attachments'] as $key=>$item ){
                            if( in_array( $item, $attachments ) == false ){
                                $order_attachments[$key]['order_id'] = $input['order_id'];
                                $order_attachments[$key]['image'] = $item;
                                $order_attachments[$key]['created_at'] = Carbon::now();
                                $order_attachments[$key]['updated_at'] = Carbon::now();
                            }
                        }
                        $order_attachments = OrderAttachments::insert($order_attachments);
                        
                        //delete those attachments which are not in supplied attachment array
                        foreach( $attachments as $key=>$item ){
                            if( in_array( $item, $input['order_attachments'] ) == false ){
                                OrderAttachments::where('order_id',$input['order_id'])->where('image',$item)->delete();
                            }
                        }
                    }

                    return response()->json(array('success'=>1,'data'=>Orders::with(['items','attachments'])->find($input['order_id']),'message'=>"Bid updated successfully.") ,200,[],JSON_NUMERIC_CHECK);
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>"Order not found.");
                return response()->json($message);
            }
            
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }

    /**
     * Negotiate order
     * 
     * @return \Illuminate\Http\Response
     * */
    public function negotiateOrder( Request $request )
    {
        $user = Auth::guard('seller')->user();
        $input = $request->all();

        // dd($input);

        $validator = Validator::make($input,[
            'order_items' => "array | nullable",
            'deleted_items' => "string | nullable",
            'delivery_method' => 'required',
            'stock_availablity' => 'required',
            'delivery_date' => 'required',
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user){
            $order = Orders::where('id',$input['order_id']);
            if( $order->exists() ){
                try
                {
                    //update items
                    $order_items = $input['order_items'];
                    $deleted_items = explode(",",$input['deleted_items']);

                    //delete items 
                    if( count($order_items) == 0 || count($deleted_items) > 0 ){
                        foreach( $deleted_items as $k=>$id ){
                            OrderItems::where('id',$id)->delete();
                        }
                    }

                    if( count($order_items) > 0 ){
                        foreach($order_items as $k=>$item){
                            OrderItems::where('id',$item['id'])
                                      ->where('order_id',$item['order_id'])
                                      ->update( [
                                                    'negotiated_quantity' => $item['quantity'], 
                                                    'negotiated_price'=>$item['price'],
                                                    'negotiated_by'=>$user->id,
                                                    'negotiated_by_role'=>'7',
                                                    'negotiation_date'=> Carbon::now(),
                                                    'quantity' => $item['quantity'], 
                                                    'price'=>$item['price'],
                                                    'is_made_in_india' => $item['is_made_in_india'],
                                                    'is_item_active' => $item['is_item_active'],
                                                    'brand_id' => $item['brand_id'],
                                                    'model_id' => $item['model_id'],
                                                ] );
                        }
                    }

                    //check if any order item exists otherwise delete order and its meta and return
                    if( ! OrderItems::where('order_id',$input['order_id'])->exists() ){
                        $attachments = OrderAttachments::where('order_id',$input['order_id']);
                        if( $attachments->exists() ){
                            foreach( $attachments->get() as $k=>$v){
                                $deleted = CommonHelpers::deleteImageFromS3( 'bid',$v->image );
                                OrderAttachments::where('id',$v->id)->forceDelete();
                            }
                        }
                        $order->delete();
                        
                        //Send notification of cancelled order
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Order cancelled",
                                    'subject' => "Order cancelled",
                                    'role' => [1],
                                    'template_data'=>$input['order_id'],
                                    'order_id' => $input['order_id'],
                                    'type' => 'order_cancelled'
                                );
                        CommonHelpers::sendMailByRole( $data );

                        return response()->json(array('success'=>1,'data'=>[],"order_deleted"=>true,'message'=>"This order is deleted successfully.") ,200,[],JSON_NUMERIC_CHECK);
                    
                    //update order
                    }else{
                        $orderData = [
                                        'negotiation_status'=>1,
                                        'negotiated_on'=>Carbon::now(),
                                        'negotiated_by'=>$user->id,
                                        'negotiated_by_role'=>'7',
                                        'negotiated_by_role_type'=>'2',
                                        'delivery_method'=>$input['delivery_method'],
                                        'stock_availablity'=>$input['stock_availablity'],
                                        'due_date'=>Carbon::createFromTimestamp($input['delivery_date'])->toDateTimeString()
                                    ];
                                    
                        $negotiatedMessage = $input['negotiated_message'] ? $input['negotiated_message'] : '' ;

                        if( trim($negotiatedMessage) != ''){
                            $orderData['negotiated_message_seller'] = $negotiatedMessage;
                        }

                        $order->update($orderData);

                        $order = Orders::with(['items','attachments'])->find($input['order_id']);

                        //Send notification of negotiated order
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Bid negotiated",
                                    'subject' => "Bid negotiated",
                                    'role' => [1],
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'bid_negotiated'
                                );
                        CommonHelpers::sendMailByRole( $data );

                        return response()->json(array('success'=>1,'data'=>$order,"order_deleted"=>false,'message'=>"Bid negotiated successfully.") ,200,[],JSON_NUMERIC_CHECK);
                    }




                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>"Order not found.");
                return response()->json($message);
            }
            
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }


    /**
     * Delete bid/order attachment
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteOrderAttachment(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        // dd($input);

        $validator = Validator::make($input,[
            'attachment_id' => [Rule::exists('order_attachments','id')->where(function ($query) {
                                        return $query->where('deleted_at', '=', null);
                                    })
                            ,'numeric'],
            'attachment_url' => "required | url"
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {
                $deleted = CommonHelpers::deleteImageFromS3( 'bid',$input['attachment_url'] );
                if( $deleted['status'] ){
                    if( $input['attachment_id'] ){
                        OrderAttachments::where('id',$input['attachment_id'])->forceDelete();
                    }
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
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }

    /**
     * My bid
     *
     * @return \Illuminate\Http\Response
     */
    public function myBids(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'limit' => 'numeric | nullable',
            'page' => 'numeric | nullable',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {
                $limit = $request->limit ? $request->limit : 10;

                //get inprogress orders
                $orders = Orders::with(["items","attachments"])
                                ->where('seller_id', $seller->id)
                                ->where('order_status',0)
                                ->orderBy('created_at', 'desc')
                                ->paginate($limit);
                
                $orders = $orders->toArray();

                return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Bids fetched successfully.") ,200,[],JSON_NUMERIC_CHECK);
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }



    /**
     * Reject offer 
     * 
     * @return \Illuminate\Http\Response
     * */
    public function rejectOffer( Request $request )
    {
        $seller = Auth::guard('seller')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            if( Orders::where('id',$input['order_id'])->where('seller_id',$seller->id)->exists() ){
                try
                {
                    $orderData = ['order_status'=>8];

                    $rejectedMessage = $input['rejected_message'] ? $input['rejected_message'] : '' ;
                    if( trim($rejectedMessage) != ''){
                        $orderData['rejected_message_seller'] = $rejectedMessage;
                    }

                    Orders::where('id',$input['order_id'])->where('seller_id',$seller->id)->update($orderData);
                    $message = array('success'=>1,'message'=>"Offer rejected successfully.");
                    return response()->json($message);
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                 $message = array('success'=>0,'message'=>"Sorry the order does not belong to current seller!");
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }


    /**
     * Accept offer 
     * 
     * @return \Illuminate\Http\Response
     * */
    public function acceptOffer( Request $request )
    {
        $seller = Auth::guard('seller')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            if( Orders::where('id',$input['order_id'])->where('seller_id',$seller->id)->exists() ){
                try
                {
                    Orders::where('id',$input['order_id'])->where('seller_id',$seller->id)->update(['order_status'=>1]);
                    $message = array('success'=>1,'message'=>"Offer accepted successfully.");
                    return response()->json($message);
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                 $message = array('success'=>0,'message'=>"Sorry the order does not belong to current seller!");
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }

    /**
     * List orders by status
     *
     * @return \Illuminate\Http\Response
     */
    public function listOrdersByStatus( Request $request )
    {
        $seller = Auth::guard('seller')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'order_status' => 'required',
            'limit' => 'numeric | nullable',
            'page' => 'numeric | nullable',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if( $seller ){
            try
            {
                $limit = $request->limit ? $request->limit : 10;

                $orders = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails'])
                            ->where('orders.seller_id',$seller->id)
                            ->where('orders.order_status',$input['order_status'])
                            ->orderBy('id','desc')
                            ->paginate($limit);
                            // ->get()
                            // ->toArray();
                $orders = $orders->toArray();

                // $total = count($orders);

                // foreach ($orders as $index => $order) {
                //     $newItemArray = array();
                //     foreach ($order['items'] as $item) {
                //         if(count($newItemArray)>0){
                //             $arrayLength = count($newItemArray);
                //             $key = array_search($item['item_brand'], array_column($newItemArray, 'brand'));
                //             if(is_numeric($key)){
                //                 array_push($newItemArray[$key]['items'],$item);
                //             }else{
                //                 $newItemArray[$arrayLength]['brand'] = $item['item_brand'];
                //                 $newItemArray[$arrayLength]['items'] = array();
                //                 array_push($newItemArray[$arrayLength]['items'],$item);
                //             }
                //         }else{
                //             $newItemArray[0]['brand'] = $item['item_brand'];
                //             $newItemArray[0]['items'] = array();
                //             array_push($newItemArray[0]['items'],$item);
                //         }
                //     }
                //     $orders[$index]['items'] = $newItemArray;
                // }
                return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }

    
    /**
     * Get bid/order detail
     *
     * @return \Illuminate\Http\Response
     */
    public function orderDetail(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {
                $order = Orders::with(["items","attachments"])->where('seller_id', $seller->id)->where('id', $input['order_id']);
                if( $order->exists() ){
                    $orders = Orders::with(["items","attachments","seller","pickupdetails"])->where('seller_id', $seller->id)->where('id', $input['order_id'])->get();
                    return response()->json(array('success'=>1,'data'=>$orders,'message'=>"Bids fetched successfully.") ,200,[],JSON_NUMERIC_CHECK);
                }
                else{
                    return response()->json(array('success'=>0,'message'=>"No bid or order found.") ,200,[],JSON_NUMERIC_CHECK);
                }
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }


    /**
     * List all orders
     *
     * @return \Illuminate\Http\Response
     */
    public function allOrders( Request $request )
    {
        $seller = Auth::guard('seller')->user();

        if( $seller ){
            try
            {
                $orders = Orders::where('orders.seller_id',$seller->id)
                                ->orderBy('id','desc')
                                ->pluck('id');
                            
                $orders = $orders->toArray();
                return response()->json(array('success'=>1,'data'=>$orders,'message'=>"Orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }

}