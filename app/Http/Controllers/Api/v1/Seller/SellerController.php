<?php

namespace App\Http\Controllers\Api\v1\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Sellers;
use App\Models\Faq;
use App\Models\Feedbacks;
use App\Models\Tickets;
use App\Models\TicketComments;

use App\CommonHelpers;

use Carbon\Carbon;
use Auth,File,URL;

class SellerController extends Controller
{
    
     /**
     * Faqs 
     *
     * @return \Illuminate\Http\Response
     */
    public function getFaqs(Request $request)
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

                $faqs = Faq::whereIsActive(1)->paginate($limit);

                $faqs = $faqs->toArray();
                
                $message = array('success'=>1,'data'=>$faqs['data'],'current_page'=>$faqs['current_page'],'last_page'=>$faqs['last_page'],'total_results'=>$faqs['total'],'message'=>"FAQs listed successfully.");
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
     * Add feedback
     *
     * @return \Illuminate\Http\Response
     */
    public function addFeedback(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'description' => "string | nullable",
            // 'image' => "required | string"
            'feedback_rating' => "required | string"
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {   
                $input['submitted_by'] = $seller->id;
                $input['submitter_role'] = 7;

                $feedback = Feedbacks::create($input);

                if( $feedback ){
                    return response()->json(array('success'=>1,'data'=>$feedback ,'message'=>"Feedback submitted successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }


    /**
     * Create query
     *
     * @return \Illuminate\Http\Response
     */
    public function createQuery(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'subject' => "required | string",
            'description' => "required | string",
            'images' => "string | nullable"
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {   

                $input['user_id'] = $seller->id;
                $input['user_role'] = 7;

                $query = Tickets::create($input);

                if( $query ){
                    return response()->json(array('success'=>1,'data'=>$query ,'message'=>"Query submitted successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }

    /**
     * Comment on query
     *
     * @return \Illuminate\Http\Response
     */
    public function commentOnQuery(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'ticket_id' => ['required',Rule::exists('tickets','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),'numeric'],
            'comment' => "required | string",
            'images' => "string | nullable"
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($seller){
            try
            {
                $input['commented_by'] = $seller->id;
                $input['user_role'] = 7;

                $comment = TicketComments::create($input);

                if( $comment ){
                    Tickets::whereId($input['ticket_id'])->update(['have_comments'=>1,'is_comment'=>1,'is_read'=>0]);
                    return response()->json(array('success'=>1,'data'=>$comment ,'message'=>"Comment submitted successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * Get query details
     *
     * @return \Illuminate\Http\Response
     */
    public function getQueryDetails(Request $request, $id)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        if($seller){
            try
            {
                $queryDetail = Tickets::with('comments')->where('id',$id)->first();
                
                //update is_read
                $queryDetail->update(['is_read'=>1]);
                TicketComments::where('ticket_id',$id)
                                        ->update(['is_read'=>1]);

                if( $queryDetail ){
                    return response()->json(array('success'=>1,'data'=>$queryDetail ,'message'=>"Query details fetched successfully.") ,200,[],JSON_NUMERIC_CHECK);
                }else{
                    return response()->json(array('success'=>0,'message'=>"Something went wrong please try again !") ,200,[],JSON_NUMERIC_CHECK);
                }
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }
        else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }


     /**
     * List all queries
     *
     * @return \Illuminate\Http\Response
     */
    public function listQueries(Request $request)
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

                $queries = Tickets::
                                // with('comments')
                                where('user_id',$seller->id)
                                ->where('user_role',$seller->user_role)
                                ->orderby('id','DESC')
                                ->paginate($limit)
                                ->toArray();

                if( $queries ){
                    return response()->json(array('success'=>1,'data'=>$queries['data'],'current_page'=>$queries['current_page'],'last_page'=>$queries['last_page'],'total_results'=>$queries['total'],'message'=>"Queries fetched successfully.") ,200,[],JSON_NUMERIC_CHECK);
                }else{
                    return response()->json(array('success'=>0,'message'=>"Something went wrong please try again !") ,200,[],JSON_NUMERIC_CHECK);
                }
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }
        else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }

    /**
     * All order status list
     *
     * @return \Illuminate\Http\Response
     */
    public function allOrderStatusList(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        if( $seller ){
            try
            {
                return response()->json(array('success'=>1,'data'=>config('smartebiz.order_status'),'message'=>"All order status listed.") ,200,[],JSON_NUMERIC_CHECK);
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }
        else{
            $message = array('success'=>0,'message'=>"Seller not found.");
            return response()->json($message);
        }
    }


     /**
     * Activate Deactivate user notifications status
     * 
     * @return \Illuminate\Http\Response
     * */
    public function setNotifications( Request $request )
    {
        $seller = Auth::guard('seller')->user();

        $input = $request->all();
        $validator = Validator::make($input,[            
            'status' => ['required','boolean']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if( $seller ){
            try
            {
                $updated =  Sellers::whereId($seller->id)->update(['app_notifications'=>$input['status']]);

                $message = array('success'=>1,'message'=>$input['status']?'Notifications activated.':'Notifications deactivated.');
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
     * Get profile data
     * 
     * @return \Illuminate\Http\Response
     * */
    public function getMyProfile( Request $request )
    {
        $seller = Auth::guard('seller')->user();

        $input = $request->all();
        $validator = Validator::make($input,[  
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if( $seller ){
            try
            {
              $message = array('success'=>1,'data'=>$seller->load('userRole'),'message'=>'Profile fetched!');
              return response()->json($message);
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
     * Edit seller profile
     * 
     * @return \Illuminate\Http\Response
     * */
    public function editSellerProfile( Request $request )
    {
        $seller = Auth::guard('seller')->user();
        $input = $request->all();

        $messages = [
            'name.required' => 'Please enter a full valid URL',
            // 'phone_number.required' => 'Please enter a valid phone number.',
            // 'phone_number.unique' => 'Phone number is already registered.',
            // 'aadhaar_number.size' => 'Aadhaar number must be of 12 digits.',
        ];

        $validator = Validator::make($input,[

            'name'     => 'required|max:55',
            // 'phone_number'   => ['required',Rule::unique('sellers')->where(function ($query) use ($seller){
            //                                     return $query->where('deleted_at', '=', null)->where('id', '!=', $seller->id);
            //                                 })
            //             ,'numeric'],
                        
            // 'aadhaar_number' => "required | digits:12",
            // 'aadhaar_front_image' => "required | url",
            // 'aadhaar_back_image' => "required | url",

            // 'pan_number' => "required | alpha_num | size:10",
            // 'pan_image' => "required | url",

            // 'gst_number' => "required | alpha_num",
            // 'gst_image' => "required | url",

            // 'cheque_number' => "required | alpha_num",
            // 'cheque_image' => "required | url",

            'profile_image' => " nullable | url",

            // 'address' => "required | string",
            // 'pincode' => "required | integer",
            
        ],$messages);

        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        // dd($input);

        if( $seller && $seller->user_role == '7' ){
            try
            {
                $updateSeller = $seller;

                $updateSeller->name = $input['name'];
                // $updateSeller->phone_number = $input['phone_number'];

                // $updateSeller->aadhaar_number = $input['aadhaar_number'];
                // $updateSeller->aadhaar_front_image = $input['aadhaar_front_image'];
                // $updateSeller->aadhaar_back_image = $input['aadhaar_back_image'];
                
                // $updateSeller->pan_number = $input['pan_number'];
                // $updateSeller->pan_image = $input['pan_image'];

                // $updateSeller->gst_number = $input['gst_number'];
                // $updateSeller->gst_image = $input['gst_image'];

                // $updateSeller->cheque_number = $input['cheque_number'];
                // $updateSeller->cheque_image = $input['cheque_image'];

                // $updateSeller->address = $input['address'];
                // $updateSeller->pincode = $input['pincode'];

                $updateSeller->profile_image = $input['profile_image'] ? $input['profile_image'] : null;

                    if($updateSeller->save()){
                    $message = array('success'=>1,'data'=> $seller->refresh(), 'message'=>'Profile updated.');
                    return response()->json($message);
                }else{
                    $message = array('success'=>0,'message'=>'Something went wrong!.');
                    return response()->json($message);
                }
            
            }
            catch(Exception $e)
            {
              $message = array('success'=>0,'message'=>$e->getMessage());
              return response()->json($message);
            }
        }else{
            $message = array('success'=>0,'message'=>"Not authorized.");
            return response()->json($message);
        }
    }

    

}
