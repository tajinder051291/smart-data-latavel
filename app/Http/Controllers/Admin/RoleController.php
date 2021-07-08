<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\AppPermissions;
use App\Models\UserRoles;
use File,URL;
use DB;
use Illuminate\Support\Facades\Storage;

class RoleController extends Controller
{

    public function roleList(Request $request)
    {
        if($request->has('search') && $request->search != ''){
            $search = trim($request->search);
            $roles  = UserRoles::where('name','LIKE',"%{$search}%")->paginate(10);
        }else{
            $roles = UserRoles::paginate(10);
        }
        foreach ($roles as $role) {
            $permissions=AppPermissions::whereIn('id', explode(',',$role->app_permission_id))->select(DB::raw('group_concat(name) as names'))->get()->toArray();
            if(count($permissions)>0){
                $role->permissions=$permissions[0]['names'];
            }else{
                $role->permissions="";
            }
        }
        return view('Admin.UsersRole.roles',['roles'=>$roles]);
    }

    public function addRoleForm(Request $request){
        $appPermission = AppPermissions::where('is_active',1)->get();
        return view('Admin.UsersRole.addRoles',['appPermissions'=> $appPermission]);
    }

    public function addRole(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'name' => 'required|unique:user_roles,name',
            'appPermissionIds' => 'required'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $params['name'] = trim($params['name']);
            $params['app_permission_id'] = implode(',',$params['appPermissionIds']);
            $roles = UserRoles::create($params);
            if($roles){
                return redirect('/admin/role-management')->withInput()->with('success','Added successfully.');
            }else{
                return redirect()->back()->withInput()->with('error','Somthing went wrong!');
            }
        }
    }

    public function editRoleForm(Request $request,$id)
    {
        $id = base64_decode($id);
        $roles = UserRoles::find($id);
        $appPermission = AppPermissions::where('is_active',1)->get();
        return view('Admin.UsersRole.editRoles',['appPermissions'=> $appPermission,'roles'=>$roles]);
    }

    public function editRole(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'name' => 'required|unique:user_roles,name,'.$params['id'],
            'appPermissionIds' => 'required'
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $userRole = UserRoles::find($params['id']);
            if($userRole){
                
                $userRole->name = trim($params['name']);
                $userRole->app_permission_id = implode(',',$params['appPermissionIds']);
            
                if($userRole->save()){
                    return redirect('/admin/role-management')->with('success','Update successfully!');
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
            $roles = UserRoles::find($params['id']);
            if($roles){
                if($roles->delete()){
                    $message = array('success'=>true,'message'=>'Delete successfully.');
                    return json_encode($message);
                }else{
                    $message = array('success'=>false,'message'=>'Somthing went wrong!');
                    return json_encode($message);
                }
            }
        }
    }

    public function activeInActiveRoles(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $role = UserRoles::find($params['id']);
            if($role){
                if($params['status'] == 1){
                    $role->is_active = 1;
                    if($role->save()){
                        $message = array('success'=>true,'message'=>'Activate successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }else{
                    $role->is_active = 0;
                    if($role->save()){
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

}
