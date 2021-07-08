<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatConnection;

use Auth;
use Carbon\Carbon;

class ChatController extends Controller
{
    /**
     * Send chat message
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage( Request $request)
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();

        $message = array(
            'image.required' => "Message and image both can't be empty",
            'message.required' => "Message and image both can't be empty",
        );
        $validator = Validator::make($input,[

            'connection_id' => ['string', 'nullable'],
            'sender_id'   => ['required',Rule::exists('users','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),'numeric'],
            'receiver_id' => ['required',Rule::exists('users','id')->where(function ($query) {
                                return $query->where('deleted_at', '=', null);
                            }),'numeric'],
            'message'     => [ Rule::requiredIf(function () use ($request) {
                                return $request->image == null;
                            }),'string','nullable'],
            'image'       => [ Rule::requiredIf(function () use ($request) {
                                return $request->message == null;
                            }),'string','nullable'],
            'message_time'   =>'required',
            'is_read' => 'required',
            'is_sent' => 'required',
            ],$message);

        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret); 
        }

        if( $input['sender_id'] == $input['receiver_id']){
            $message = array('success'=>0,'message'=>'Sender and receiver cannot be same !');
            return response()->json($message);
        }

        if( $user ){

            $input['message_time']  = Carbon::createFromTimestamp($input['message_time']);

            try{
                if( $input['connection_id'] == null ){ //if chat connection doesn't exist

                    $already  = ChatConnection::where([ 
                                                            ['sender_id','=',$input['sender_id']],
                                                            ['receiver_id','=',$input['receiver_id']]
                                                        ])
                                                    ->orWhere([
                                                            ['sender_id','=',$input['receiver_id']],
                                                            ['receiver_id','=',$input['sender_id']]
                                                        ]);
                    //check if chat connection exists
                    if( $already->exists() ) {
                        $message = array('success'=>0,'message'=>'Please send valid connection id !');
                        return response()->json($message);
                    }

                    if($input['sender_id']>$input['receiver_id']){
                        $input['connection_id'] = $input['receiver_id'].'-'.$input['sender_id'];
                    }else{
                        $input['connection_id']=$input['sender_id'].'-'.$input['receiver_id'];
                    }

                    $connection = ChatConnection::create( [ 'sender_id'=> $input['sender_id'] , 'receiver_id'=> $input['receiver_id'], 'connection_id'=>$input['connection_id'] ] );
                    
                    if( $connection ){
                        $input ['user_id'] = $input['sender_id'];
                        $chat = Chat::create( $input );
                        //$input['connection_id'] = $connection->id;
                    }else{
                        $message = array('success'=>0,'message'=>'Something went wrong please try again !');
                        return response()->json($message);
                    }

                }else{ //if chat connection exists
                    
                    $validConnection  = ChatConnection::where([ 
                                                                ['connection_id',$input['connection_id']],
                                                                ['sender_id',$input['sender_id']],
                                                                ['receiver_id',$input['receiver_id']]
                                                            ])
                                                      ->orWhere([
                                                                ['connection_id',$input['connection_id']],
                                                                ['sender_id',$input['receiver_id']],
                                                                ['receiver_id',$input['sender_id']] 
                                                            ]);
                    $input ['user_id'] = $input['sender_id'];
                    if( $validConnection->exists() ){
                        $chat = Chat::create( $input );
                    }else{
                        $connection = ChatConnection::create( [ 'sender_id'=> $input['sender_id'] , 'receiver_id'=> $input['receiver_id'], 'connection_id'=>$input['connection_id'] ] );
                        $chat = Chat::create( $input );
                    }
                }

                //notification

                return response()->json(array('success'=>1,'data'=>$chat,'message'=>"Message sent successfully.") ,200,[],JSON_NUMERIC_CHECK);

            }catch(Exception $e){

                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        } else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }

    }



    /**
     * Get chat details
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function getChatDetails( Request $request , Chat $chat)
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
                        'connection_id' => ['string', 'required'],
                        'limit' => 'numeric | nullable',
                        'page' => 'numeric | nullable',
                    ]);

        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if( $user ){
            try{

                $limit = $request->limit ? $request->limit : 10;
                
                $chat = $chat->where('connection_id',$input['connection_id'])->paginate($limit)->toArray();

                return response()->json(array('success'=>1,'data'=>$chat['data'],'current_page'=>$chat['current_page'],'last_page'=>$chat['last_page'],'total_results'=>$chat['total'],'message'=>"Chat details listed successfully.") ,200,[],JSON_NUMERIC_CHECK);

            }catch(Exception $e){

                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        } else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }

    }


    /**
     * List all chats
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChatConnection  $chatConnection
     * @return \Illuminate\Http\Response
     */
    public function listAllChats( Request $request , ChatConnection $chatConnection )
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
            try{
                $limit = $request->limit ? $request->limit : 10;
                
                $chatConnection = $chatConnection->with('latestMessages')
                                                ->with('senderDetails')
                                                ->with('receiverDetails')
                                                ->where(function($query) use($user){
                                                            $query->where([
                                                                ['sender_id','=',$user->id],
                                                            ])
                                                            ->orWhere([
                                                                ['receiver_id','=',$user->id],
                                                            ]);
                                                    })
                                                ->get();  

                $sorted = array_values($chatConnection->sortByDesc('latestMessages.message_time')->toArray());
                
               // return response()->json(array('success'=>1,'data'=>$sorted,'current_page'=>$chatConnection['current_page'],'last_page'=>$chatConnection['last_page'],'total_results'=>$chatConnection['total'],'message'=>"Chat details listed successfully.") ,200,[],JSON_NUMERIC_CHECK);
                return response()->json(array('success'=>1,'data'=>$sorted,'message'=>"Chat details listed successfully.") ,200,[],JSON_NUMERIC_CHECK);

            }catch(Exception $e){
                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        } else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }

    }


    /**
     * Set message as read
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function setMessageAsRead( Request $request , Chat $chat )
    {
        $user = Auth::guard('user')->user();

        $input = $request->all();
        $validator = Validator::make($input,[
                        'connection_id' =>  ['required',Rule::exists('chat_connection','connection_id')->where(function ($query) {
                                                return $query->where('deleted_at', '=', null);
                                            })]         
                    ]);

        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        if( $user ){
            try{

                $validConnection  = ChatConnection::where([ 
                                                            ['connection_id','=',$input['connection_id']],
                                                            ['sender_id','=',$user->id]
                                                        ])
                                                    ->orWhere([
                                                            ['connection_id','=',$input['connection_id']],
                                                            ['receiver_id','=',$user->id]
                                                        ]);
                if( $validConnection->exists() ){

                    $read = Chat::where( [
                                            ['connection_id','=', $input['connection_id']],
                                            ['user_id', '!=', $user->id],
                                            ['is_read','=', 0],
                                        ])
                                        ->update(['is_read'=>1]);

                    return response()->json(array('success'=>1,'message'=>"Messages set as read.") ,200,[],JSON_NUMERIC_CHECK);


                }else{
                    $message = array('success'=>0,'message'=>'Connection id is not valid !');
                    return response()->json($message);
                }
               
            }catch(Exception $e){

                $message = array('success'=>0,'message'=>$e->getMessage());
                return response()->json($message);
            }
        } else{
            $message = array('success'=>0,'message'=>"User not found.");
            return response()->json($message);
        }

    }


}
