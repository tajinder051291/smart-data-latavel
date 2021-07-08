<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


use App\Models\MobileBrands;
use App\Models\MobileModels;

use App\Models\User;
use App\Models\Sellers;

use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\OrderAttachments;
use App\Models\OrderInvoices;
use App\Models\PickupDetails;
use App\Models\WarehouseDetails;
use App\Models\DeliveryPartners;

use App\CommonHelpers;
use App\FirebaseHelper;

use Carbon\Carbon;
use Auth,File,URL, Mail;

class OrdersController extends Controller
{
    /**
     * List all recieved orders
     *
     * @return \Illuminate\Http\Response
     */
    public function list_old(Request $request)
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user){
            try
            {
                $orders = Orders::with(['items','attachments','seller'])
                            ->where('orders.order_status',0)
                            ->orderBy('id','desc')
                            ->get()->toArray();

                foreach ($orders as $index => $order) {
                    $newItemArray = array();
                    foreach ($order['items'] as $item) {
                        if(count($newItemArray)>0){
                            $arrayLength = count($newItemArray);
                            $key = array_search($item['item_brand'], array_column($newItemArray, 'brand'));
                            if(is_numeric($key)){
                                array_push($newItemArray[$key]['items'],$item);
                            }else{
                                $newItemArray[$arrayLength]['brand'] = $item['item_brand'];
                                $newItemArray[$arrayLength]['items'] = array();
                                array_push($newItemArray[$arrayLength]['items'],$item);
                            }
                        }else{
                            $newItemArray[0]['brand'] = $item['item_brand'];
                            $newItemArray[0]['items'] = array();
                            array_push($newItemArray[0]['items'],$item);
                        }
                    }
                    $orders[$index]['items'] = $newItemArray;
                }
                return response()->json(array('success'=>1,'data'=>$orders,'message'=>"Orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * List all recieved orders
     *
     * @return \Illuminate\Http\Response
     */
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
            try
            {
                $limit = $request->limit ? $request->limit : 10;

                $orders = Orders::with(['items','attachments','seller'])
                                ->whereIn('orders.order_status',['0','1'])
                                ->orderBy('id','desc')
                                ->paginate($limit);
                                // ->get();
                $orders = $orders->toArray();
               
                return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * Accept order 
     * 
     * @return \Illuminate\Http\Response
     * */
    public function acceptOrder( Request $request )
    {
        $user = Auth::guard('user')->user();

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

        if($user){
            try
            {
                
                $order = Orders::where('id',$input['order_id'])->first();

                //Send notification of order removed to seller
                $data = array(
                            'template' => "emails.bidplaced",
                            'message' => "Order accepted",
                            'subject' => "Order accepted",
                            'seller_id' => $order->seller_id,
                            'template_data'=>$order,
                            'order_id' => $input['order_id'],
                            'type' => 'order_accepted'
                        );
                
                $order->update(['order_status'=>1]);

                CommonHelpers::sendMailToSeller( $data );

                $message = array('success'=>1,'message'=>"Order accepted.");
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
     * Negotiate order
     * 
     * @return \Illuminate\Http\Response
     * */
    public function negotiateOrder( Request $request )
    {
        $user = Auth::guard('user')->user();
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
                                                    'negotiated_by_role'=>$user->user_role,
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

                        $order = $order->first();

                        //Send notification of order removed to seller
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Order removed",
                                    'subject' => "Order removed",
                                    'seller_id' => $order->seller_id,
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'order_cancelled'
                                );

                        $order->delete();

                        CommonHelpers::sendMailToSeller( $data );


                        return response()->json(array('success'=>1,'data'=>[],"order_deleted"=>true,'message'=>"This order is deleted successfully.") ,200,[],JSON_NUMERIC_CHECK);
                    
                    //update order
                    }else{                       

                        $orderData = [
                                      'negotiation_status'=>1,
                                      'negotiated_on'=>Carbon::now(),'negotiated_by'=>$user->id,'negotiated_by_role'=>$user->user_role,
                                      'negotiated_by_role_type'=>'1',
                                      'delivery_method'=>$input['delivery_method'],
                                      'stock_availablity'=>$input['stock_availablity'],
                                      'due_date'=>Carbon::createFromTimestamp($input['delivery_date'])->toDateTimeString()
                                    ];
                        $negotiatedMessage = $input['negotiated_message'] ? $input['negotiated_message'] : '' ;
                        if( trim($negotiatedMessage) != ''){
                            $orderData['negotiated_message_manager'] = $negotiatedMessage;
                        }

                        $order->update($orderData);

                        $order = Orders::with(['items','attachments'])->find($input['order_id']);

                        //Send notification of order negotiated to seller
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Order negotiated",
                                    'subject' => "Order negotiated",
                                    'seller_id' => $order->seller_id,
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'bid_negotiated'
                                );
                        CommonHelpers::sendMailToSeller( $data );

                        return response()->json(array('success'=>1,'data'=>$order,"order_deleted"=>false,'message'=>"Bid updated successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }


    }


     /**
     * Update Invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function updateInvoice(Request $request)
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',
                            Rule::exists('orders','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),
                            'numeric'
                          ],
            'invoice_number' => ['required',
                                    Rule::exists('order_invoices','invoice_number')->where(function ($query) {
                                        return $query->where('deleted_at', '=', null);
                                    }),
                                    'numeric'
                                ],
            'payment_details' => "required | string",
            'payment_attachment' => 'required | string'
        ]);

        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user){
            try
            {
                $invoice = $invoice_update =  OrderInvoices::where( 'order_id', $input['order_id'] )->where( 'invoice_number', $input['invoice_number'] );
                if( $invoice_update->exists() ){
                    $status = $invoice_update->update( [ 'payment_details' => $input['payment_details'],'payment_attachment'=> $input['payment_attachment'],'invoice_status' => 1 ]);
                    if( $status ){
                        return response()->json(array('success'=>1,'data'=>$invoice->first() ,'message'=>"Invoice payment updated.") ,200,[],JSON_NUMERIC_CHECK);
                    }else{
                        return response()->json(array('success'=>0,'message'=>"Please try again !") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }else{
                    return response()->json(array('success'=>0,'message'=>"Order invoice not found.") ,200,[],JSON_NUMERIC_CHECK);
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


    
     /**
     * Order payment invoices detail
     *
     * @return \Illuminate\Http\Response
     */
    public function orderPaymentInvoices(Request $request)
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',
                            Rule::exists('orders','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),
                            'numeric'
                        ],
            'invoice_status' => "boolean"
        ]);

        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if($user){
            try
            {
                $orders = Orders::with(['invoices','items','attachments','seller'])
                                ->where('id',$input['order_id'])
                                ->get()->toArray();

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

                return response()->json(array('success'=>1,'data'=>$orders[0] ,'message'=>"Payment invoices listed.") ,200,[],JSON_NUMERIC_CHECK);

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
     * List only accepted orders
     *
     * @return \Illuminate\Http\Response
     */
    public function acceptedOrdersList(Request $request)
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

        if($user && $user->user_role == 2){
            try
            {
                $limit = $request->limit ? $request->limit : 10;

                $orders = Orders::with(['items','attachments','seller','invoices'])
                                ->whereIn('orders.order_status',['1'])
                                ->orderBy('id','desc')
                                ->paginate($limit);
                $orders = $orders->toArray();
               
                return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Accepted bids listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * List assigned orders
     *
     * @return \Illuminate\Http\Response
     */
    public function assignedPickups(Request $request)
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'user_role' => 'required',   //1=LG, 2=DP

            'limit' => 'numeric | nullable',
            'page' => 'numeric | nullable',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user && $user->user_role == 2){
            try
            {
                $limit = $request->limit ? $request->limit : 10;

                $pickupOrders = PickupDetails::where([
                                                'pickup_details.pickup_by_role'=>$input['user_role']
                                            ])->pluck('order_id');

                $orders = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails'])
                                // ->whereIn('orders.order_status',['4'])
                                ->whereIn('orders.id',$pickupOrders )
                                ->orderBy('id','desc')
                                ->paginate($limit);
                                 
                $orders = $orders->toArray();
               
                return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * Add order pickup information
     *
     * @return \Illuminate\Http\Response
     */
    public function addPickup(Request $request)
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'pickup_by' => "required | numeric",
            'pickup_by_role' => "required | numeric",
            'pickup_type' => "required | numeric",
            'order_id' => ['required',
                            Rule::exists('orders','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),
                            'numeric'
                          ],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user && $user->user_role == 2){
            try
            {
                $order = $orderUpdate =  Orders::where('id',$input['order_id']);

                if( $orderUpdate->first()->order_status == '2'){//pickup already added
                    return response()->json(array('success'=>0,'message'=>"Pickup is already added !") ,200,[],JSON_NUMERIC_CHECK);
                }

                $orderUpdate = $orderUpdate->update(['order_status'=>'2',]);
                $pickup = PickupDetails::create($input);

                if( $pickup ){

                    $order = $order->with('seller','items','invoices','attachments','pickupdetails')->first() ;

                    //Send notification regarding this pickup to manager
                    $data = array(
                                'template' => "emails.bidplaced",
                                'message' => "Pickup assigned for a order.",
                                'subject' => "Pickup assigned for a order.",
                                'role' => [1,4,5],   //Fortbell,LG,DP
                                'template_data'=>$order,
                                'order_id' => $input['order_id'],
                                'type' => 'pickup_assigned'
                            );
                    CommonHelpers::sendMailByRole( $data );

                    //Send notification regarding this pickup to seller
                    $data = array(
                                'template' => "emails.bidplaced",
                                'message' => "Pickup assigned for your order.",
                                'subject' => "Pickup assigned for your order.",
                                'seller_id' => $order->seller_id,
                                'template_data'=>$order,
                                'order_id' => $input['order_id'],
                                'type' => 'pickup_assigned'
                            );
                    CommonHelpers::sendMailToSeller( $data );

                    return response()->json(array('success'=>1,'data'=>$order,'message'=>"Pickup confirmed.") ,200,[],JSON_NUMERIC_CHECK);
                }else{
                    return response()->json(array('success'=>0,'message'=>"Something went wrong please try again !") ,200,[],JSON_NUMERIC_CHECK);
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


     /**
     * List all algined orders for LG user
     *
     * @return \Illuminate\Http\Response
     */
    public function alignedLogisticOrders(Request $request)
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

        // dd($user->user_role);

        if( $user ){
            if( $user->user_role == '4' ){
                try
                {
                    $limit = $request->limit ? $request->limit : 10;

                    $pickupOrders = PickupDetails::where([
                                                'pickup_details.pickup_type'=>'2',
                                                'pickup_details.pickup_by_role'=>'4',
                                                'pickup_details.pickup_by'=>$user->id,
                                            ])->pluck('order_id');

                    $orders = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails'])
                                    ->whereIn( 'order_status',['2','3']) // Pickup added or Pickup confirmed
                                    ->whereIn( 'id',$pickupOrders)
                                    ->orderBy('id','desc')
                                    ->paginate($limit);
                                    // ->get();
                    $orders = $orders->toArray();
                
                    return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Aligned orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>"Not authorized.");
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }


     /**
     * Get aligned order detail (LG user)
     *
     * @return \Illuminate\Http\Response
     */
    public function alignedLogisticOrderDetail(Request $request)
    {
        $user = Auth::guard('user')->user();
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

        if($user){
            if($user->user_role = '4'){
                try
                {
                   $order = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails'])
                                    ->where( [ 
                                                'orders.id'=>$input['order_id'],
                                                // 'orders.order_status'=>'2'
                                            ]);
                    if( $order->exists() ){
                        $order = $order->first();
                        return response()->json(array('success'=>1,'data'=>$order,'message'=>"Aligned order details fetched successfully.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                    else{
                        return response()->json(array('success'=>0,'message'=>"No order found.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{

            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }


    /**
     * Confirm stock (LG user)
     *
     * @return \Illuminate\Http\Response
     */
    public function confirmStockLogistic(Request $request)
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'pickup_remarks' => ['string','nullable'],
            'pickup_images' => ['required','string']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if($user){
            if($user->user_role = '4'){
                try
                {
                    $pickup = PickupDetails::where('order_id',$input['order_id'])->update($input);
                    $order = $orderUpdated  = Orders::whereId($input['order_id']);
                    $orderUpdated = $orderUpdated->update(['order_status'=>'3']);

                    if( $pickup && $orderUpdated ){

                        $order = $order->with('seller','items','attachments','invoices','pickupdetails','warehousedetails')->first();

                        //Send notification of pickup up order to managers
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Order pickup confirmed",
                                    'subject' => "Order pickup confirmed",
                                    'role' => [1,2], //Fortbell , Buyer
                                    'template_data'=>$pickup,
                                    'order_id' => $input['order_id'],
                                    'type' => 'pickup_confirmed'
                                );
                        CommonHelpers::sendMailByRole( $data );

                        //Send notification of pickup up order to seller
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Order pickup confirmed",
                                    'subject' => "Order pickup confirmed",
                                    'seller_id' => $order->seller_id,
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'pickup_confirmed'
                                );
                        CommonHelpers::sendMailToSeller( $data );

                        
                        return response()->json(array('success'=>1,'data'=>$order,'message'=>"Pickup confirmed successfully.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                    else{
                        return response()->json(array('success'=>0,'message'=>"No order found.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{

            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }



    /**
     * Deposit stock (LG user)
     *
     * @return \Illuminate\Http\Response
     */
    public function depositStockLogistic(Request $request)
    {
        $user = Auth::guard('user')->user();
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

        if($user){
            if($user->user_role = '4'){
                try
                {
                    if( Orders::whereId($input['order_id'])->where('order_status','4')->exists() ){
                        return response()->json(array('success'=>0,'message'=>"You can only deposit an order's stock once.") ,200,[],JSON_NUMERIC_CHECK);
                    }

                    $order = $orderUpdated  = Orders::whereId($input['order_id']);
                    $orderUpdated = $orderUpdated->update(['order_status'=>'4']); //pickup_deposited

                    if( $orderUpdated ){

                        $order = $order->with('seller','items','attachments','invoices','pickupdetails','warehousedetails')->first();

                         //Send notification of pickup up order to managers
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Stock deposited",
                                    'subject' => "Stock deposited",
                                    'role' => [1,2,3], //Fortbell , Buyer, warehouse
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'stock_deposited'
                                );
                        CommonHelpers::sendMailByRole( $data );

                        //Send notification of pickup up order to seller
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Stock deposited",
                                    'subject' => "Stock deposited",
                                    'seller_id' => $order->seller_id,
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'stock_deposited'
                                );
                        CommonHelpers::sendMailToSeller( $data );


                        return response()->json(array('success'=>1,'data'=>$order,'message'=>"Stock deposited.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                    else{
                        return response()->json(array('success'=>0,'message'=>"No order found.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{

            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }



    /**
     * List all delivered orders by LG user
     *
     * @return \Illuminate\Http\Response
     */
    public function lgOrdersByStatus(Request $request)
    {
        $user = Auth::guard('user')->user();

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

        if( $user ){
            if( $user->user_role == '4' ){
                try
                {
                    $limit = $request->limit ? $request->limit : 10;


                    $pickupOrders = PickupDetails::where([
                                                'pickup_details.pickup_type'=>'2',
                                                'pickup_details.pickup_by_role'=>'4',
                                                'pickup_details.pickup_by'=>$user->id,
                                            ])->pluck('order_id');

                    $orders = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails']);

                    if( $input['order_status'] == 1 ) {
                        $orders =  $orders->whereIn('orders.order_status',['2','3'])
                                          ->whereIn('orders.id',$pickupOrders)
                                          ->orderBy('id','desc')
                                          ->paginate($limit);
                    }

                    if( $input['order_status'] == 2 ) {
                        $orders =  $orders->where('orders.order_status',4)
                                          ->whereIn('orders.id',$pickupOrders)
                                          ->orderBy('id','desc')
                                          ->paginate($limit);
                    }

                    $orders = $orders->toArray();
                
                    return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Assigned orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>"Not authorized.");
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }



    /**
     * List all warehouse orders
     *
     * @return \Illuminate\Http\Response
     */
    public function listWarehouseOrders(Request $request)
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'limit' => 'numeric | nullable',
            'page' => 'numeric | nullable',
            'order_status' => ['numeric', 'required',Rule::in(['4','5','6'])]
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        // dd($user->user_role);

        if( $user ){
            if( $user->user_role == '3' ){
                try
                {
                    $limit = $request->limit ? $request->limit : 10;

                    $orders = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails'])
                                    // ->whereIn( 'order_status',['3','4']) // pickup confirmed or deposited
                                    ->whereIn( 'order_status',[$input['order_status']])
                                    ->orderBy('id','desc')
                                    ->paginate($limit);
                                    // ->get();
                    $orders = $orders->toArray();
                
                    return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Orders arriving , on hold or deposited in warehouse are listed.") ,200,[],JSON_NUMERIC_CHECK);
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>"Not authorized.");
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }


    /**
     * Accept stock (Warehouse user)
     *
     * @return \Illuminate\Http\Response
     */
    public function acceptStock(Request $request)
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'warehouse_remarks' => ['string','nullable'],
            'warehouse_images' => ['required','string'],
            'warehouse_received_quantity' => ['required'],
            'warehouse_received_quality' => ['required'],
            'warehouse_stocks_with_issue' => Rule::requiredIf(function () use ($input) {
                return $input['warehouse_received_quality'] == '2';
            }),
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if($user){
            if($user->user_role = '3'){
                try
                {
                    if( Orders::whereId($input['order_id'])->where('order_status','6')->exists() ){ //received
                        return response()->json(array('success'=>0,'message'=>"Order already received.") ,200,[],JSON_NUMERIC_CHECK);
                    }

                    $received = WarehouseDetails::create($input);
                    $order = $orderUpdated  = Orders::whereId($input['order_id']);
                    $orderUpdated = $orderUpdated->update(['order_status'=>'6']);

                    if( $received && $orderUpdated ){
                        return response()->json(array('success'=>1,'data'=>$order->with('seller','items','attachments','invoices','pickupdetails','warehousedetails','warehousedetails')->first(),'message'=>"Order received successfully.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                    else{
                        return response()->json(array('success'=>0,'message'=>"No order found.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{

            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }



    /**
     * Hold stock (Warehouse user)
     *
     * @return \Illuminate\Http\Response
     */
    public function holdStock(Request $request)
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'warehouse_remarks' => ['string','nullable'],
            'warehouse_images' => ['required','string'],
            'warehouse_received_quantity' => ['required'],
             'warehouse_received_quality' => ['required'],
            'warehouse_stocks_with_issue' => Rule::requiredIf(function () use ($input) {
                return $input['warehouse_received_quality'] == '2';
            }),
            
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if($user){
            if($user->user_role = '3'){
                try
                {
                    if( Orders::whereId($input['order_id'])->where('order_status','5')->exists() ){ //received
                        return response()->json(array('success'=>0,'message'=>"Order status is already set as hold.") ,200,[],JSON_NUMERIC_CHECK);
                    }

                    $hold = WarehouseDetails::create($input);
                    $order = $orderUpdated  = Orders::whereId($input['order_id']);
                    $orderUpdated = $orderUpdated->update(['order_status'=>'5']);

                    $order = $order->with('seller','items','attachments','invoices','pickupdetails','warehousedetails','warehousedetails')->first();

                     //Send notification regarding this pickup to manager
                    $data = array(
                                'template' => "emails.bidplaced",
                                'message' => "Stock put on hold at warehouse.",
                                'subject' => "Stock put on hold at warehouse.",
                                'role' => [1,2],   //Fortbell,Buyer
                                'template_data'=>$order,
                                'order_id' => $input['order_id'],
                                'type' => 'on_hold'
                            );
                    CommonHelpers::sendMailByRole( $data );

                    

                    if( $hold && $orderUpdated ){
                        return response()->json(array('success'=>1,'data'=>$order,'message'=>"Order put on hold successfully.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                    else{
                        return response()->json(array('success'=>0,'message'=>"No order found.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{

            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
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
        $user = Auth::guard('user')->user();
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

        if($user){
            try
            {
                $order = $updated =  Orders::with(["items","attachments","seller",'invoices','pickupdetails','warehousedetails'])->where('id', $input['order_id']);
                $exists = $order->first();

                if( $exists ){
                    if( $exists->is_read == 0){
                       $updated->update(['is_read'=>1,'is_read_by'=>$user->id]);
                    }
                    $order = $exists->refresh();
                    return response()->json(array('success'=>1,'data'=>$order,'message'=>"Bid detail fetched successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }

    /**
     * Get orders by status
     *
     * @return \Illuminate\Http\Response
     */
    public function listOrdersByStatus(Request $request)
    {
        $user = Auth::guard('user')->user();

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

        if($user){
            try
            {
                $limit = $request->limit ? $request->limit : 10;

                $orders = Orders::with([
                                            'items',
                                            'attachments',
                                            'seller',
                                            'invoices',
                                            'pickupdetails',
                                            'warehousedetails'
                                        ]);

                if( $input['order_status'] == 0 ) {
                    $orders =  $orders->where('orders.order_status',0);
                }

                if( $input['order_status'] == 1 ) {
                    $orders =  $orders->whereIn('orders.order_status',['1','2','3','4','5','6']);
                }

                if( $input['order_status'] == 7 ) {
                    $orders =  $orders->where('orders.order_status','7');
                }

                if( $input['order_status'] == 8 ) {
                    $orders =  $orders->where('orders.order_status','8');
                }


                /* Seller Filter */
                if( isset($input['filter_seller']) ){
                    $orders = $orders->where('orders.seller_id','=',$input['filter_seller']);
                }
                // dd(Carbon::createFromTimestamp($input['filter_due_date']));
                /* Due Date Filter */
                if( isset($input['filter_due_date']) ){
                    $orders = $orders->whereDate('orders.due_date','=',Carbon::createFromTimestamp($input['filter_due_date'])->toDateString());
                }

                /* Model Filter */
                if( isset($input['filter_model']) ){
                    // $orders = $orders->leftJoin( 'order_items',function ($join) use($input) {
                    //             $join->on('orders.id', '=', 'order_items.order_id')
                    //                 ->whereIn('order_items.model_id',explode(',',$input['filter_model']));
                    //             });
                    $orders = $orders->whereHas('items', function (Builder $query) use($input){
                                    $query->where('model_id', '=', $input['filter_model']);
                                });
                }
                
                // dd($orders->orderBy('orders.id','desc')->toSql() , $orders->orderBy('orders.id','desc')->getBindings());

                $orders = $orders->select('orders.*')
                                 ->distinct()
                                 ->orderBy('orders.id','desc')
                                 ->paginate($limit);
                                
                $orders = $orders->toArray();

                // dd($orders);
               
                return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * Warehouse orders manager
     *
     * @return \Illuminate\Http\Response
     */
    public function warehouseOrdersForManager(Request $request)
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'limit' => 'numeric | nullable',
            'page' => 'numeric | nullable',
            'order_status' => [ 'required',Rule::in(['4','5','6']),] ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if($user){
            try
            {
                $limit = $request->limit ? $request->limit : 10;

                $orders = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails']);

                $orders =  $orders->where('orders.order_status',$input['order_status'])
                                ->orderBy('id','desc')
                                ->paginate($limit);

                $orders = $orders->toArray();

                return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Warehouse stocks listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * List all aligned orders for DP user
     *
     * @return \Illuminate\Http\Response
     */
    public function alignedDPOrders(Request $request)
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

        // dd($user->user_role);

        if( $user ){
            if( $user->user_role == '8' ){
                try
                {
                    $limit = $request->limit ? $request->limit : 10;

                    $pickupOrders = PickupDetails::where([
                                                'pickup_details.pickup_type'=>'1',
                                                'pickup_details.pickup_by_role'=>'8',
                                                'pickup_details.pickup_by'=>$user->id,
                                            ])->pluck('order_id');

                    $orders = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails'])
                                    ->whereIn( 'order_status',['2','3']) // Pickup added or Pickup confirmed
                                    ->whereIn( 'id',$pickupOrders)
                                    ->orderBy('id','desc')
                                    ->paginate($limit);
                                    // ->get();
                    $orders = $orders->toArray();
                
                    return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Aligned orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>"Not authorized.");
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }


      /**
     * Get aligned order detail (DP user)
     *
     * @return \Illuminate\Http\Response
     */
    public function alignedDPOrderDetail(Request $request)
    {
        $user = Auth::guard('user')->user();
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

        if($user){
            if($user->user_role = '8'){
                try
                {
                   $order = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails'])
                                    ->where( [ 
                                                'orders.id'=>$input['order_id'],
                                                'orders.order_status'=>'2'
                                            ]);
                    if( $order->exists() ){
                        $order = $order->first();
                        return response()->json(array('success'=>1,'data'=>$order,'message'=>"Aligned order details fetched successfully.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                    else{
                        return response()->json(array('success'=>0,'message'=>"No order found.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{

            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }

    /**
     * Confirm stock (DP user)
     *
     * @return \Illuminate\Http\Response
     */
    public function confirmStockDeliveryPartner(Request $request)
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'pickup_remarks' => ['string','nullable'],
            'pickup_images' => ['required','string']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if($user){
            if($user->user_role = '8'){
                try
                {
                    $pickup = PickupDetails::where('order_id',$input['order_id'])->update($input);
                    $order = $orderUpdated  = Orders::whereId($input['order_id']);
                    $orderUpdated = $orderUpdated->update(['order_status'=>'3']);

                    if( $pickup && $orderUpdated ){

                        $order = $order->with('seller','items','attachments','invoices','pickupdetails','warehousedetails')->first();
                        
                        // Send notification of pickup up order
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Order pickup confirmed",
                                    'subject' => "Order pickup confirmed",
                                    'role' => [1],
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'pickup_confirmed'
                                );
                        CommonHelpers::sendMailByRole( $data );


                        //Send notification of pickup up order to seller
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Order pickup confirmed",
                                    'subject' => "Order pickup confirmed",
                                    'seller_id' => $order->seller_id,
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'pickup_confirmed'
                                );
                        CommonHelpers::sendMailToSeller( $data );


                        return response()->json(array('success'=>1,'data'=>$order,'message'=>"Pickup confirmed successfully.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                    else{
                        return response()->json(array('success'=>0,'message'=>"No order found.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{

            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }


    /**
     * Deposit stock (DP user)
     *
     * @return \Illuminate\Http\Response
     */
    public function depositStockDeliveryPartner(Request $request)
    {
        $user = Auth::guard('user')->user();
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

        if($user){
            if($user->user_role = '8'){
                try
                {
                    if( Orders::whereId($input['order_id'])->where('order_status','4')->exists() ){
                        return response()->json(array('success'=>0,'message'=>"You can only deposit an order's stock once.") ,200,[],JSON_NUMERIC_CHECK);
                    }

                    $order = $orderUpdated  = Orders::whereId($input['order_id']);
                    $orderUpdated = $orderUpdated->update(['order_status'=>'4']); //pickup_deposited

                    if( $orderUpdated ){

                        $order = $order->with('seller','items','attachments','invoices','pickupdetails','warehousedetails')->first();

                        //Send notification of pickup up order to managers
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Stock deposited",
                                    'subject' => "Stock deposited",
                                    'role' => [1,2,3], //Fortbell , Buyer, warehouse
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'stock_deposited'
                                );
                        CommonHelpers::sendMailByRole( $data );

                        //Send notification of pickup up order to seller
                        $data = array(
                                    'template' => "emails.bidplaced",
                                    'message' => "Stock deposited",
                                    'subject' => "Stock deposited",
                                    'seller_id' => $order->seller_id,
                                    'template_data'=>$order,
                                    'order_id' => $input['order_id'],
                                    'type' => 'stock_deposited'
                                );
                        CommonHelpers::sendMailToSeller( $data );

                        return response()->json(array('success'=>1,'data'=>$order,'message'=>"Stock deposited.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                    else{
                        return response()->json(array('success'=>0,'message'=>"No order found.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{

            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }



    /**
     * Dispatch stock (DP user)
     *
     * @return \Illuminate\Http\Response
     */
    public function dispatchStockDeliveryPartner(Request $request)
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',Rule::exists('orders','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'dispatch_remarks' => ['string','nullable'],
            'dispatch_tracking_id' => ['required','string'],
            'dispatch_images' => ['string']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if($user){
            if($user->user_role = '8'){
                try
                {
                    if( Orders::whereId($input['order_id'])->where('order_status','6')->exists() ){
                        return response()->json(array('success'=>0,'message'=>"Order is already dispatched.") ,200,[],JSON_NUMERIC_CHECK);
                    }
    
                    $dispatch = warehousedetails::create($input);

                    $order = $orderUpdated  = Orders::whereId($input['order_id']);
                    $orderUpdated = $orderUpdated->update(['order_status'=>'6']); //dispatched

                    if( $orderUpdated ){
                        return response()->json(array('success'=>1,'data'=>$order->with('seller','items','attachments','invoices','pickupdetails','warehousedetails')->first(),'message'=>"Order dispatched to fortbell.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                    else{
                        return response()->json(array('success'=>0,'message'=>"No order found.") ,200,[],JSON_NUMERIC_CHECK);
                    }
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{

            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }

       /**
     * List all delivered orders by LG user
     *
     * @return \Illuminate\Http\Response
     */
    public function dpOrdersByStatus(Request $request)
    {
        $user = Auth::guard('user')->user();

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

        if( $user ){
            if( $user->user_role == '8' ){
                try
                {
                    $limit = $request->limit ? $request->limit : 10;


                    $pickupOrders = PickupDetails::where([
                                                'pickup_details.pickup_type'=>'1',
                                                'pickup_details.pickup_by_role'=>'8',
                                                'pickup_details.pickup_by'=>$user->id,
                                            ])->pluck('order_id');

                    $orders = Orders::with(['items','attachments','seller','invoices','pickupdetails','warehousedetails']);

                    if( $input['order_status'] == 1 ) {
                        $orders =  $orders->whereIn('orders.order_status',['2','3'])
                                          ->whereIn('orders.id',$pickupOrders)
                                          ->orderBy('id','desc')
                                          ->paginate($limit);
                    }

                    if( $input['order_status'] == 2 ) {
                        $orders =  $orders->where('orders.order_status',4)
                                          ->whereIn('orders.id',$pickupOrders)
                                          ->orderBy('id','desc')
                                          ->paginate($limit);
                    }

                    $orders = $orders->toArray();
                
                    return response()->json(array('success'=>1,'data'=>$orders['data'],'current_page'=>$orders['current_page'],'last_page'=>$orders['last_page'],'total_results'=>$orders['total'],'message'=>"Assigned orders listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
                }
                catch(Exception $e)
                {
                    $message = array('success'=>0,'message'=>$e->getMessage());
                    return response()->json($message);
                }
            }
            else{
                $message = array('success'=>0,'message'=>"Not authorized.");
                return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }


     /**
     * List all received orders
     *
     * @return \Illuminate\Http\Response
     */
    public function allOrders(Request $request , Orders $orders)
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if($user){
            try
            {
                $allOrders = $orders->get();

                $data = array(
                            'total' => $allOrders->count(),
                            // 'all_order_status' => $allOrders->pluck('order_status'),
                            'completed' => $allOrders->whereIn('order_status',[6,7])->count(),
                            'cancelled' => $allOrders->whereIn('order_status',[5,8])->count(),
                            'inprocess' => $allOrders->whereIn('order_status',[2,3,4])->count(),
                            'pending' => $allOrders->whereIn('order_status',[0,1])->count(),
                            'today' => array(
                                        'inprocess' => $orders->whereIn('order_status',[2,3,4])->whereDate('created_at', Carbon::today()->toDateString())->count(),
                                        'pending'=>  $orders->whereIn('order_status',[0,1])->whereDate('created_at', Carbon::today()->toDateString())->count(),
                            ),
                            'current_month' => array(
                                        'inprocess'=>$orders->whereIn('order_status',[2,3,4])->whereMonth('created_at', Carbon::today()->month)->count(),
                                        'pending'=>$orders->whereIn('order_status',[0,1])->whereMonth('created_at', Carbon::today()->month)->count(),
                            ),
                            'current_year' => array(
                                        'inprocess'=>$orders->whereIn('order_status',[2,3,4])->whereYear('created_at', Carbon::today()->year)->count(),
                                        'pending'=>$orders->whereIn('order_status',[0,1])->whereYear('created_at', Carbon::today()->year)->count(),
                            ),
                        );
               
                return response()->json(array('success'=>1,'data'=>$data,'message'=>"All Orders details listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * Get model types
     *
     * @return \Illuminate\Http\Response
     */
    public function getModelTypes(Request $request)
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'models' => "required | array"
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user){
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
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }

}
