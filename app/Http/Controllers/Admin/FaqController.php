<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\AppPermissions;
use App\Models\Faq;
use File,URL;
use DB;
use Illuminate\Support\Facades\Storage;

class FaqController extends Controller
{

    public function faqList(Request $request)
    {
        if($request->has('search') && $request->search != ''){
            $search = trim($request->search);
            $faq  = Faq::where('title','LIKE',"%{$search}%")->orderBy('is_active', 'DESC')->paginate(10);
        }else{
            $faq = Faq::orderBy('is_active', 'DESC')->paginate(10);
        }
        return view('Admin.Faq.faq',['faqs'=>$faq]);
    }

    public function addFaqForm(Request $request){
        return view('Admin.Faq.addFaq');
    }

    public function addFaq(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'title' => 'required|unique:faq,title',
            'description' => 'required'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $params['title'] = trim($params['title']);
            $params['description'] = trim($params['description']);
            $faq = Faq::create($params);
            if($faq){
                return redirect('/admin/faq')->withInput()->with('success','Added successfully.');
            }else{
                return redirect()->back()->withInput()->with('error','Somthing went wrong!');
            }
        }
    }

    public function editFaqForm(Request $request,$id)
    {
        $id  = base64_decode($id);
        $faq = Faq::find($id);
        return view('Admin.Faq.editFaq',['faq'=> $faq]);
    }

    public function editFaq(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'title' => 'required|unique:faq,title,'.$params['id'],
            'description' => 'required'
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $faq = Faq::find($params['id']);
            if($faq){

                $faq->title = trim($params['title']);
                $faq->description = trim($params['description']);
            
                if($faq->save()){
                    return redirect('/admin/faq')->with('success','Update successfully!');
                }else{
                    return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
                }
            }else{
                return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
            }
        }
    }

    public function deleteRole(Request $request){

        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $faq = Faq::find($params['id']);
            if($faq){
                if($faq->delete()){
                    $message = array('success'=>true,'message'=>'Delete successfully.');
                    return json_encode($message);
                }else{
                    $message = array('success'=>false,'message'=>'Somthing went wrong!');
                    return json_encode($message);
                }
            }
        }
    }

    public function activeInActiveFaq(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $faq = Faq::find($params['id']);
            if($faq){
                if($params['status'] == 1){
                    $faq->is_active = 1;
                    if($faq->save()){
                        $message = array('success'=>true,'message'=>'Publish successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }else{
                    $faq->is_active = 0;
                    if($faq->save()){
                        $message = array('success'=>true,'message'=>'Unpublish successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }
            }
        }
    }    

}
