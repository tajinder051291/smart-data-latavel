<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Feedbacks;

class FeedbackController extends Controller
{
    /**
     * List feedbacks
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
       if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $feedbacks  = Feedbacks::where('description','LIKE',"%{$search}%")
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        }else{
            $feedbacks = Feedbacks::orderBy('created_at', 'desc')->paginate(10);
        }

        // dd($feedbacks->toArray());

        return view('Admin.Feedbacks.list',['feedbacks'=>$feedbacks]);
    }

}
