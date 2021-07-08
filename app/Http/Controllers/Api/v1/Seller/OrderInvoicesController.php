<?php

namespace App\Http\Controllers\Api\v1\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

// use App\Models\MobileBrands;
// use App\Models\MobileModels;
use App\Models\Orders;
// use App\Models\OrderItems;
// use App\Models\OrderAttachments;
use App\Models\OrderInvoices;

use App\CommonHelpers;
use Carbon\Carbon;
use Auth,File,URL,Mail;

class OrderInvoicesController extends Controller
{
    /**
     * Create Invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function createInvoice(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',
                            Rule::exists('orders','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),
                            'numeric'
                          ],
            'invoice_number' => "required | numeric",
            'invoice_date' => "required | numeric",
            'invoice_amount' => 'required | numeric| between:0,99999999999.99',
            'bank_details' => 'required | string',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {
                if( Orders::where('id',$input['order_id'])->pluck('order_status')[0] == 1 ){ //create invoice only if order is accepted
                    if( OrderInvoices::where( 'order_id', $input['order_id'] )->where( 'invoice_number', $input['invoice_number'] )->exists() ){
                        return response()->json(array('success'=>0,'message'=>"Provided invoice number already exists for this order.") ,200,[],JSON_NUMERIC_CHECK);
                    }

                    $input['invoice_date'] = Carbon::createFromTimestamp($input['invoice_date'])->toDateString();
                    //create invoice
                    $invoice = OrderInvoices::create($input);

                    //Send notification of created invoice
                    $data = array(
                                'template' => "emails.bidplaced",
                                'message' => "Invoice created",
                                'subject' => "Invoice created",
                                'role' => [1,6],  // fortbell,accounts
                                'template_data'=>$invoice,
                                'order_id' => $input['order_id'],
                                'type' => 'invoice_created'
                            );
                    CommonHelpers::sendMailByRole( $data );

                    return response()->json(array('success'=>1,'data'=>$invoice->refresh() ,'message'=>"Order invoice created successfully.") ,200,[],JSON_NUMERIC_CHECK);
                } 
                else{
                    
                    return response()->json(array('success'=>0,'message'=>"Order is not accepted yet.") ,200,[],JSON_NUMERIC_CHECK);

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
     * Search order invoices
     *
     * @return \Illuminate\Http\Response
     */
    public function searchOrderInvoices(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'order_id' => ['required',
                            Rule::exists('orders','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),
                            'numeric'
                        ],
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

                $invoices = OrderInvoices::where('order_id',$input['order_id'])
                                        // ->with(['order'])
                                        ->paginate($limit);
                
                $invoices = $invoices->toArray();
                
                return response()->json(array('success'=>1,'data'=>$invoices['data'],'current_page'=>$invoices['current_page'],'last_page'=>$invoices['last_page'],'total_results'=>$invoices['total'],'message'=>"Payment invoices listed.") ,200,[],JSON_NUMERIC_CHECK);

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
