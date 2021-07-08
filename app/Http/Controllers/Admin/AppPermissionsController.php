<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\AppPermissions;
use File,URL;
use Illuminate\Support\Facades\Storage;

class AppPermissionsController extends Controller
{

    public function appPermissionsList(Request $request)
    {
        if($request->has('search') && $request->search != ''){
            $search = trim($request->search);
            $appPermission  = AppPermissions::where('name','LIKE',"%{$search}%")->orderBy('created_at', 'desc')->paginate(10);
        }else{
            $appPermission = AppPermissions::orderBy('created_at', 'desc')->paginate(10);
        }
        return view('Admin.AppPermissions.appPermissions',['appPermissions'=>$appPermission]);
    }

    public function addAppPermissionForm(Request $request){
        return view('Admin.AppPermissions.addAppPermission');
    }

    public function addAppPermission(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'name' => 'required|unique:app_permissions,name'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $params['name'] = trim($params['name']);
            $admin = AppPermissions::create($params);
            if($admin){
                return redirect('/admin/app-permissions')->withInput()->with('success','App Permission added successfully.');
            }else{
                return redirect()->back()->withInput()->with('error','Somthing went wrong!');
            }
        }
    }

    public function editAppPermissionForm(Request $request,$id)
    {
        $id = base64_decode($id);
        $appPermission = AppPermissions::find($id);
        return view('Admin.AppPermissions.editAppPermission',['appPermission'=> $appPermission]);
    }

    public function editAppPermission(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'name' => 'required|unique:app_permissions,name,'.$params['id']
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $appPermission = AppPermissions::find($params['id']);
            if($appPermission){
                $appPermission->name = trim($params['name']);
                if($appPermission->save()){
                    return redirect('/admin/app-permissions')->with('success','Update App Permission info successfully!');
                }else{
                    return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
                }
            }else{
                return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
            }
        }
    }

    public function deleteAppPermission(Request $request){

        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $appPermission = AppPermissions::find($params['id']);
            if($appPermission){
                if($appPermission->delete()){
                    $message = array('success'=>true,'message'=>'Delete successfully.');
                    return json_encode($message);
                }else{
                    $message = array('success'=>false,'message'=>'Somthing went wrong!');
                    return json_encode($message);
                }
            }
        }
    } 

    public function activeInActivePermission(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $permission = AppPermissions::find($params['id']);
            if($permission){
                if($params['status'] == 1){
                    $permission->is_active = 1;
                    if($permission->save()){
                        $message = array('success'=>true,'message'=>'Activate successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }else{
                    $permission->is_active = 0;
                    if($permission->save()){
                        $message = array('success'=>true,'message'=>'Deactivate successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }
            }
        }
    } 

    public function deleteSelectedPermissions(Request $request)
    {
        $params = $request->all();
       
        $validation = Validator::make($params,[
            'ids' => 'required'
        ]);
        if ($validation->fails()) {
            $success_message = array('success'=>false,"message"=>$validation->messages()->first());
            return json_encode($success_message);
        }else{
            $ids = explode(",",$params['ids']);
            AppPermissions::WhereIn('id',$ids)->delete();
            $success_message = array('success'=>true,"message"=>"Deleted successfully.");
            return json_encode($success_message);
        }
    }

}
