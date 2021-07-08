<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Tickets;
use App\Models\TicketComments;

class TicketsController extends Controller
{
    /**
     * List feedbacks
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request, $id)
    {
       if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $queries  = Tickets::where('subject','LIKE',"%{$search}%");
            if( $id != '2' )$queries->where('is_active',$id);
            $queries  = $queries->orWhere('description','LIKE',"%{$search}%")
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10);
        }else{

            if( $id != '2' ) $queries = Tickets::where('is_active',$id)->orderBy('created_at', 'desc')->paginate(10);
            else $queries = Tickets::orderBy('created_at', 'desc')->paginate(10);

        }

        // dd($queries->toArray());

        return view('Admin.Queries.list',['queries'=>$queries]);
    }

    /**
     * List comments
     *
     * @return \Illuminate\Http\Response
     */
    public function queryDetails(Request $request, $id)
    {
        $id  = base64_decode($id);

        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $ticketDetail  = Tickets::with('comments')
                                    ->where('id',$id)
                                    ->where('comment','LIKE',"%{$search}%")
                                    ->first();
        }else{
            $ticketDetail = Tickets::with('comments')->where('id',$id)->first();
        }

        //update is_read value
        $ticketDetail->update(['is_read'=>1]);

        // dd($ticketDetail);
         //update is_read
        TicketComments::where('ticket_id',$id)
                      ->where('user_role','=','7')
                      ->update(['is_read'=>1]);

        return view('Admin.Queries.chatPage',['ticketDetail'=>$ticketDetail]);
    }

    /**
     * Close query/ticket
     *
     * @return \Illuminate\Http\Response
     */
    public function closeQuery(Request $request, $id)
    {
        $id  = base64_decode($id);

        $ticketDetail = Tickets::where('id',$id)->update(['is_active'=>0]);
        if(  $ticketDetail ){
            return redirect('admin/query/list/2')->withInput()->with('success','Query closed successfully.');
        }else{
            return redirect()->back()->withInput()->with('error',"Please try again !");
        }
    }

    /**
     * Message in query/ticket
     *
     * @return \Illuminate\Http\Response
     */
    public function commentOnQuery(Request $request)
    {
        $is_admin = \Auth::guard('admin')->check() ? true : false;

        if( $is_admin ):
        $user = \Auth::guard('admin')->user();
        $user_role = 0;
        else:
        $user = \Auth::guard('manager')->user();
        $user_role = $user->user_role;
        endif;
        // dd($user);

        $input = $request->all();
        // dd($input);
        $input['ticket_id'] = base64_decode($input['ticket_id']);

        $validator = Validator::make($input,[
            'comment' => "required | string",
            'ticket_id' => ['required',Rule::exists('tickets','id')->where(function ($query) {
                return $query->where('deleted_at', '=', null);
            }),'numeric'],
        ]);
        if($validator->fails()) {
            $ret = array('success'=>0, 'message'=> $validator->messages()->first());
            return response()->json($ret);
        }

        try
        {
            $input['commented_by'] = $user->id;
            $input['user_role'] = $user_role;
            $comment = TicketComments::create($input);
            return response()->json(array('success'=>1,'data'=>$comment,'message'=>"Message sent.") ,200,[],JSON_NUMERIC_CHECK);
        }
        catch(Exception $e)
        {
            $message = array('success'=>0,'message'=>$e->getMessage());
            return response()->json($message);
        }
    }



}
