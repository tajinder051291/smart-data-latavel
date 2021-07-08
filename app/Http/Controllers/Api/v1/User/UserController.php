<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\UserRoles;
use App\Models\States;
use App\Models\User;
use App\Models\UserTeams;
use App\Models\Sellers;
use App\Models\SellerGroups;
use App\Models\Faq;
use App\Models\Feedbacks;
use App\Models\Tickets;
use App\Models\TicketComments;

use App\CommonHelpers;

use Carbon\Carbon;
use Auth,File,URL;

class UserController extends Controller
{    
     /**
     * UPload file
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function uploadFile(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input,[
            'file'  => 'required | file',
            'type' => 'required | string',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        try{
            // $fileUrl = "https://dummy.url";
            $fileUrl = \App\CommonHelpers::uploadImageToS3( $input['type'] ,$input['file']);
            if( gettype($fileUrl) == "object" ){
                $message = array('success'=>0,'message'=>"File was not uploaded. Please try again.",'error'=>$fileUrl->getMessage());
                return response()->json($message);
            }

            return response()->json(array('success'=>1,'data'=>$fileUrl,'message'=>"File saved successfully."),200,[],JSON_NUMERIC_CHECK);
        }
        catch(Exception $e)
        {
        $message = array('success'=>0,'message'=>$e->getMessage());
        return response()->json($message);
        }
    }


     /**
     * Faqs 
     *
     * @return \Illuminate\Http\Response
     */
    public function getFaqs(Request $request)
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
        $user = Auth::guard('user')->user();
        $input = $request->all();

        $validator = Validator::make($input,[
            'description' => "string | nullable",
            'feedback_rating' => "required | string"
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user){
            try
            {   
                $input['submitted_by'] = $user->id;
                $input['submitter_role'] = $user->user_role;

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
            $message = array('success'=>0,'message'=>"User not found.");
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
        $user = Auth::guard('user')->user();
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

        if($user){
            try
            {
                $input['user_id'] = $user->id;
                $input['user_role'] = $user->user_role;

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
            $message = array('success'=>0,'message'=>"User not found.");
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
        $user = Auth::guard('user')->user();
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

        if($user){
            try
            {
                $input['commented_by'] = $user->id;
                $input['user_role'] = $user->user_role;

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
        $user = Auth::guard('user')->user();
        $input = $request->all();

        if($user){
            try
            {
                $query = Tickets::with('comments')->where('id',$id);
                $queryDetail = $query->first();

                //update is_read
                TicketComments::where('ticket_id',$id)
                            //   ->where('user_role','=','7')
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
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }



     /**
     * Get list of Logistic Users
     *
     * @return \Illuminate\Http\Response
     */
    public function listLogisticUsers(Request $request)
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

        if($user && $user->user_role == 2 ){
            try
            {

                $limit = $request->limit ? $request->limit : 10;
               
                $lg = User::where('is_active',1)
                        ->where('user_role','=','4')
                        ->paginate($limit);
                        // ->get();

                $lg = $lg->toArray();

                return response()->json(array('success'=>1,'data'=>$lg['data'],'current_page'=>$lg['current_page'],'last_page'=>$lg['last_page'],'total_results'=>$lg['total'],'message'=>"Logistic users fetched successfully.") ,200,[],JSON_NUMERIC_CHECK);
               
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }
        else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }





    /**
     * List delivery partners
     *
     * @return \Illuminate\Http\Response
     */
    public function listDeliveryPartners(Request $request)
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

        if($user && $user->user_role == 2 ){
            try
            {

                $limit = $request->limit ? $request->limit : 10;
               
                $lg = User::where('is_active',1)
                        ->where('user_role','=','8')
                        ->paginate($limit);
                        // ->get();

                $lg = $lg->toArray();

                return response()->json(array('success'=>1,'data'=>$lg['data'],'current_page'=>$lg['current_page'],'last_page'=>$lg['last_page'],'total_results'=>$lg['total'],'message'=>"Delivery partners listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
               
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }
        else{
            $message = array('success'=>0,'message'=>"User not found.");
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
        $user = Auth::guard('user')->user();
        $input = $request->all();

        if( $user ){
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
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }
    



     /**
     * List team (all users)
     * 
     * @return \Illuminate\Http\Response
     * */
    public function listTeam( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'limit' => 'numeric | nullable',
            'page' => 'numeric | nullable',
            'include_inactive' => 'required | boolean',
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user){
            try
            {
                $limit = $request->limit ? $request->limit : 10;
               
                $team = User::whereNotIn('id',[$user->id])
                          ->whereNotIn('user_role',['8']); //without DP

                if( ! $input['include_inactive'] ){
                     $team->where('is_active',1);
                }

                $team  = $team ->paginate($limit)->toArray();

                return response()->json(array('success'=>1,'data'=>$team['data'],'current_page'=>$team['current_page'],'last_page'=>$team['last_page'],'total_results'=>$team['total'],'message'=>"Delivery partners listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * Activate Deactivate team member (all users)
     * 
     * @return \Illuminate\Http\Response
     * */
    public function activateDeactivateTeamMember( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'user_id' => ['required',Rule::exists('users','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'activation_status' => ['required','boolean']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user && $user->user_role == '1' && $user->id != $input['user_id']){
            try
            {
                $updated =  User::whereId($input['user_id'])->update(['is_active'=>$input['activation_status']]);

                $message = array('success'=>1,'message'=>$input['activation_status']?'User activated.':'User deactivated.' ,'activation_status'=>$input['activation_status']);
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
     * Edit team member
     * 
     * @return \Illuminate\Http\Response
     * */
    public function editTeamMember( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'user_id' => ['required',Rule::exists('users','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$input['user_id'],
            'phone_number' => 'required|unique:users,phone_number,'.$input['user_id'],
            'user_role' => 'required',
            'state' => 'required'
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user && $user->user_role == '1' && $user->id != $input['user_id']){
            try
            {
                $updateUser = User::find($input['user_id']);
                if($updateUser){                  

                    $updateUser->name = $input['name'];
                    $updateUser->email = $input['email'];
                    $updateUser->phone_number = $input['phone_number'];
                    $updateUser->user_role = $input['user_role'];
                    $updateUser->state = $input['state'];
                    $updateUser->image = $input['image'] ? $input['image'] : null;

                     if($updateUser->save()){
                        $message = array('success'=>1,'data'=>$updateUser, 'message'=>'User updated.');
                        return response()->json($message);
                    }else{
                        $message = array('success'=>0,'message'=>'Something went wrong!.');
                        return response()->json($message);
                    }                   
                }
                else{
                    $message = array('success'=>0,'message'=>'User not found!');
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

    /**
     * Get user roles
     * 
     * @return \Illuminate\Http\Response
     */
    public function teamRolesList( Request $request )
    {

        $user = Auth::guard('user')->user();
        $input = $request->all();

        if( $user && $user->user_role == '1' ){
            try
            {
                $roles  = UserRoles::where('is_active',1)
                                    ->whereNotIn('id',['8','7']) //without DP
                                    ->select('id','name')
                                    ->get();

                return response()->json(array('success'=>1,'data'=>$roles,'message'=>"All team roles listed.") ,200,[],JSON_NUMERIC_CHECK);
               
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }
        else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }

    /**
     * Get states
     * 
     * @return \Illuminate\Http\Response
     */
    public function allStatesList( Request $request )
    {
        $user = Auth::guard('user')->user();
        $input = $request->all();

        if( $user && $user->user_role == '1' ){
            try
            {
                $states = States::where('country_id',101)->select('id','state')->get();
                return response()->json(array('success'=>1,'data'=>$states,'message'=>"All states listed.") ,200,[],JSON_NUMERIC_CHECK);               
            }
            catch(Exception $e)
            {
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        }
        else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }
    }



     /**
     * List sellers
     * 
     * @return \Illuminate\Http\Response
     * */
    public function listSellers( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
             'limit' => 'numeric | nullable',
             'page' => 'numeric | nullable',
             'include_inactive' => 'required | boolean'
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user){
            try
            {
                $limit = $request->limit ? $request->limit : 10;               

                if( ! $input['include_inactive'] ){
                     $sellers = Sellers::where('is_active',1)->paginate($limit);
                }else{
                    $sellers = Sellers::paginate($limit);
                }                       

                $sellers = $sellers->toArray();

                return response()->json(array('success'=>1,'data'=>$sellers['data'],'current_page'=>$sellers['current_page'],'last_page'=>$sellers['last_page'],'total_results'=>$sellers['total'],'message'=>"Sellers listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * Activate Deactivate user
     * 
     * @return \Illuminate\Http\Response
     * */
    public function activateDeactivateSeller( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'user_id' => ['required',Rule::exists('sellers','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'activation_status' => ['required','boolean']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user && $user->user_role == '1' && $user->id != $input['user_id']){
            try
            {
                $updated =  Sellers::whereId($input['user_id'])->update(['is_active'=>$input['activation_status']]);

                $message = array('success'=>1,'message'=>$input['activation_status']?'Seller activated.':'Seller deactivated.' ,'activation_status'=>$input['activation_status']);
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
     * Edit seller
     * 
     * @return \Illuminate\Http\Response
     * */
    public function editSeller( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'user_id' => ['required',Rule::exists('sellers','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'name' => 'required',
            'email' => 'required|unique:sellers,email',
            'phone_number' => 'required|unique:sellers,phone_number',
            'pincode'  => 'required'
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if($user && $user->user_role == '1' && $user->id != $input['user_id']){
            try
            {
                $updateSeller = Sellers::find($input['user_id']);
                if($updateSeller){

                    $updateSeller->name = $input['name'];
                    $updateSeller->email = $input['email'];
                    $updateSeller->phone_number = $input['phone_number'];
                    $updateSeller->pincode = $input['pincode'];
                    $updateSeller->cheque_image = $input['cheque_image'] ? $input['cheque_image'] : null;
                    $updateSeller->gst_image = $input['gst_image'] ? $input['gst_image'] : null;
                    $updateSeller->pan_image = $input['pan_image'] ? $input['pan_image'] : null;
                    $updateSeller->aadhaar_back_image = $input['aadhaar_back_image'] ? $input['aadhaar_back_image'] : null;
                    $updateSeller->aadhaar_front_image = $input['aadhaar_front_image'] ? $input['aadhaar_front_image'] : null;

                     if($updateSeller->save()){
                        $message = array('success'=>1,'data'=>$updateSeller, 'message'=>'Seller updated.');
                        return response()->json($message);
                    }else{
                        $message = array('success'=>0,'message'=>'Something went wrong!.');
                        return response()->json($message);
                    }                   
                }
                else{
                    $message = array('success'=>0,'message'=>'User not found!');
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


      /**
     * Activate Deactivate user notifications status
     * 
     * @return \Illuminate\Http\Response
     * */
    public function setNotifications( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[            
            'status' => ['required','boolean']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if( $user ){
            try
            {
                $updated =  User::whereId($user->id)->update(['app_notifications'=>$input['status']]);

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
     * List seller groups
     * 
     * @return \Illuminate\Http\Response
     * */
    public function listSellerGroups( Request $request )
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

        if( $user ){
            try
            {
                $limit = $request->limit ? $request->limit : 10;               

                $groups =  SellerGroups::where('is_active',1)
                                       ->orderBy('id','desc')
                                       ->paginate($limit)
                                       ->toArray();

                return response()->json(array('success'=>1,'data'=>$groups['data'],'current_page'=>$groups['current_page'],'last_page'=>$groups['last_page'],'total_results'=>$groups['total'],'message'=>"Seller groups successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * Assign a group to seller
     * 
     * @return \Illuminate\Http\Response
     * */
    public function assignGroupToSeller( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[       
            'seller_id' => ['required',Rule::exists('sellers','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
            'group_id' => [ 'required' ,Rule::exists('seller_groups','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null)->where('is_active', '=', 1);
            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if( $user ){
            try
            {
                $groupUpdated =  Sellers::where('id',$input['seller_id'])->update(['seller_group_id'=>$input['group_id']]);

                if( $groupUpdated  ){
                    $message = array('success'=>1,'message'=>'Seller group updated.');
                    return response()->json($message);
                }
                else{
                    $message = array('success'=>0,'message'=>'Something went wrong!');
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

    /**
     * List Teams
     * 
     * @return \Illuminate\Http\Response
     * */
    public function listTeams( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[            
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if( $user ){
            try
            {
                $limit = $request->limit ? $request->limit : 10;

                $teams =  UserTeams::where('is_active',1)
                                   ->orderBy('id','desc')
                                   ->paginate($limit)
                                   ->toArray();

                return response()->json(array('success'=>1,'data'=>$teams['data'],'current_page'=>$teams['current_page'],'last_page'=>$teams['last_page'],'total_results'=>$teams['total'],'message'=>"User teams listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
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
     * Create Team
     * 
     * @return \Illuminate\Http\Response
     * */
    public function createTeam( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[   
            'title' => ['required',Rule::unique('user_teams')->where(function ($query) {
                                                return $query->where('deleted_at', '=', null);
                                            })
                                        ],
            'members'  => ['required']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if( $user ){
            try
            {
                $input['created_by'] = $user->id;
                $input['created_by_role'] = $user->user_role;
                
                $team = UserTeams::create($input);

                $message = array('success'=>1,'data'=>$team,'message'=>'Team created.');
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
     * Edit team
     * 
     * @return \Illuminate\Http\Response
     * */
    public function editTeam( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[   
            'title' => ['required'],
            'members'  => ['required'],
            'team_id' => ['required',Rule::exists('user_teams','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if( $user ){
            try
            {
                if( UserTeams::where('title',$input['title'])->whereNotIn('id',[$input['team_id']])->exists() ){
                    $message = array('success'=>0,'message'=>'Team with same title already exists.');
                    return response()->json($message);
                }

                $team = $updated =  UserTeams::where('id',$input['team_id']);
                $updated = UserTeams::where('id',$input['team_id'])->update( ['members'=>$input['members'],'title'=>$input['title'] ] );

                if(  $updated  ){
                    $message = array('success'=>1,'data'=>$team->first(),'message'=>'Team updated.');
                    return response()->json($message);
                }   
                else{
                    $message = array('success'=>0,'message'=>'Something went wrong. Please try again.');
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



    /**
     * Create seller group
     * 
     * @return \Illuminate\Http\Response
     * */
    public function createSellersGroup( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[   
            'title' => ['required',Rule::unique('seller_groups')->where(function ($query) {
                                                return $query->where('deleted_at', '=', null);
                                            })
                                        ],
            'icon_type'  => ['required'],
            'sellers'  => ['required']
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if( $user ){
            try
            {
                $input['created_by'] = $user->id;
                $input['created_by_role'] = $user->user_role;

                $group = SellerGroups::create($input);


                $message = array('success'=>1,'data'=>$group,'message'=>'Group created.');
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
     * Edit seller group
     * 
     * @return \Illuminate\Http\Response
     * */
    public function editSellersGroup( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[   
            'title' => ['required'],
            'icon_type'  => ['required'],
            'sellers'  => ['required'],
            'group_id' => ['required',Rule::exists('seller_groups','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if( $user ){
            try
            {
                if( SellerGroups::where('title',$input['title'])->whereNotIn('id',[$input['group_id']])->exists() ){
                    $message = array('success'=>0,'message'=>'Group with same title already exists.');
                    return response()->json($message);
                }

                $group = $updated =  SellerGroups::where('id',$input['group_id']);

                $updated = $updated->update( [ 'icon_type'=>$input['icon_type'], 'sellers'=>$input['sellers'],'title'=>$input['title'] ] );

                if(  $updated  ){
                    $message = array('success'=>1,'data'=>$group->first(),'message'=>'Group updated.');
                    return response()->json($message);
                }   
                else{
                    $message = array('success'=>0,'message'=>'Something went wrong. Please try again.');
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


    /**
     * Get profile data
     * 
     * @return \Illuminate\Http\Response
     * */
    public function getMyProfile( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[  
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if( $user ){
            try
            {
              $message = array('success'=>1,'data'=>$user,'message'=>'Profile fetched!');
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
     * Edit user profil
     * 
     * @return \Illuminate\Http\Response
     * */
    public function editUserProfile( Request $request )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
            'name' => 'required',
            // 'phone_number' => 'required|unique:users,phone_number,'.$user->phone_number,
            // 'phone_number'   => ['required',
            //                       Rule::unique('users')->where(function ($query) use ($user) {
            //                             return $query->where('deleted_at', '=', null)->where('id','!=', $user->id);
            //                         })
            //                     ,'numeric'],
            // 'state' => 'required',
            'image' => 'url | nullable'
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if( $user ){
            try
            {
                $updateUser = $user;

                $updateUser->name = $input['name'];
                // $updateUser->phone_number = $input['phone_number'];
                // $updateUser->state = $input['state'];
                $updateUser->image = $input['image'];

                if($updateUser->save()){
                    $message = array('success'=>1,'data'=>$user->refresh(), 'message'=>'Profile updated.');
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

    /**
     * seller user profile
     * 
     * @return \Illuminate\Http\Response
     * */

     public function listingSellers(Request $request)
     {
        $user = Auth::guard('user')->user();
        $input = $request->all();
       
        if( $user ){
            $list=Sellers::select('id','name','phone_number')->where(['is_active'=>1,'is_verified'=>1])->get();

            $message = array('success'=>1,'message'=>'Success!.','data'=>$list);
                    return response()->json($message);
        }else{
            $message = array('success'=>0,'message'=>"Not authorized.");
            return response()->json($message);
        }
     }

}
