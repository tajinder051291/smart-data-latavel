<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Admin;
use App\Models\User;
use App\Models\Orders;
use App\Models\OrderInvoices;

use App\CommonHelpers;
use Carbon\Carbon;
use Auth,File,URL;

class OrderInvoicesController extends Controller
{
    /**
     * List order invoices
     *
     * @return \Illuminate\Http\Response
     */
    public function invoicesList(Request $request)
    {
       if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $invoices  = OrderInvoices::where('order_id','LIKE',"%{$search}%")
                            ->orWhere('invoice_number','LIKE',"%{$search}%")
                            ->orWhere('invoice_amount','LIKE',"%{$search}%")
                            ->orWhere('invoice_status','LIKE',"%{$search}%")
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        }else{
            $invoices = OrderInvoices::orderBy('created_at', 'desc')->paginate(10);
        }
        $update_allowed = (\Auth::user()->user_role == 6);

        return view('Admin.Invoices.invoices',['invoices'=>$invoices,'update_allowed'=>$update_allowed]);
    }

    /**
     * Show update order invoices form
     *
     * @return \Illuminate\Http\Response
     */
    public function showInvoicePayment(Request $request, $id)
    {
        $id = base64_decode($id);
        $invoice = OrderInvoices::find($id);

        return view('Admin.Invoices.updateInvoicePaymentDetails',['invoice'=>$invoice]);
    }


    /**
     * Update Invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function updateInvoicePayment(Request $request , $id)
    {
        $input = $request->all();
        $id = base64_decode($id);

        $update_allowed = (\Auth::user()->user_role == 6);

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
            'payment_attachment' => 'required | file'
        ]);

        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        try
        {

            $attachment = CommonHelpers::uploadImageToS3('payments',$input['payment_attachment']);
            // dd($attachment);

            $invoice = $invoice_update =  OrderInvoices::where( 'order_id', $input['order_id'] )->where( 'invoice_number', $input['invoice_number'] );
            if( $invoice_update->exists() ){
                $status = $invoice_update->update( [ 'payment_details' => $input['payment_details'],'payment_attachment'=> $attachment,'invoice_status' => 1 ]);
                if($status){
                    return redirect('/admin/invoice/payment/update/'. base64_encode($id))->with('success','Payment details updated successfully!');
                }else{
                    return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
                }
            }else{
                return redirect()->back()->withInput()->with('error','Invoice not found !');
            }
        }
        catch(Exception $e)
        {
            $message = array('success'=>0,'message'=>$e->getMessage());
            return response()->json($message);
        }

    }

}
