<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\States;
use File,URL,Mail;
use Illuminate\Support\Facades\Storage;
use App\CommonHelpers;

class UsersController extends Controller
{

    public function usersList(Request $request)
    {
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $users  = User::with('userRole','states')
                            ->where('name','LIKE',"%{$search}%")
                            ->whereIn('user_role',['1','2','3','4','5','6'])
                            ->orWhere('email','LIKE',"%{$search}%")
                            ->orWhere('phone_number','LIKE',"%{$search}%")
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        }else{
            $users = User::with('userRole','states')
                        ->whereIn('user_role',['1','2','3','4','5','6'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        }
        return view('Admin.Users.users',['users'=>$users]);
    }

    public function addUserForm(Request $request){

        $states = States::where('country_id',101)->get();
        $roles  = UserRoles::where('is_active',1)->get();
        
        return view('Admin.Users.addUsers',['states'=> $states,'userRole'=>$roles]);

    }

    public function addUser(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'user_role' => 'required',
            'state' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{

            if ($request->hasFile('display_image')) {
                $file = $request->file('display_image');
                $fileext    = $file->getClientOriginalExtension();
                $name = uniqid().'-'.time().'.'.$fileext;
                $filePath = 'profile_image/' . $name;
                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
                $params['image'] = $image_path_for_db;
            }

            $password = CommonHelpers::generateOtp(6);

            $params['password'] = bcrypt($password);
            $user = User::create($params);
            if($user){

                $userRole = UserRoles::where('id',$params['user_role'])->first();
                
                $params['password'] = $password;
                $params['phone'] = $user->phone_number;
                $params['email'] = $user->email;
                $params['role'] = $userRole->name;
                
                Mail::send('emails.email_credentials', [ 'user' => $params ,'msg'=>'Please use these credentials to access the apps' ],
                    function ($m) use ($user){
                    $m->from(config('mail.from.address'),config('app.name'));
                    $m->to($user->email)->subject('Smartebiz Login Details');
                });
                
                return redirect('/admin/users')->withInput()->with('success','User added successfully.');
            }else{
                return redirect()->back()->withInput()->with('error','Something went wrong!');
            }
            
        }
    }

    public function editUserForm(Request $request,$id)
    {
        $id = base64_decode($id);
        $user = User::find($id);
        $states = States::where('country_id',101)->get();
        $roles  = UserRoles::where('is_active',1)->get();
        
        return view('Admin.Users.editUsers',['states'=> $states,'user'=>$user,'userRole'=> $roles]);
    }

    public function editUser(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'user_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$params['user_id'],
            'phone_number' => 'required|unique:users,phone_number,'.$params['user_id'],
            'user_role' => 'required',
            'state' => 'required'
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            
            $user = User::find($params['user_id']);
            if($user){

                $uri_parts_des = explode('/', $params['oldOdisplayImg']);
                if ($request->hasFile('display_image')) {
                    $image = $request->file('display_image');
                    $fileext    = $image->getClientOriginalExtension();
                    $display_image_name = uniqid().'-'.time().'.'.$fileext;
                    $display_image_filePath = 'profile_image/' . $display_image_name;
                    $image_url = Storage::disk('s3')->put($display_image_filePath, file_get_contents($image),'public');
                    $display_image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$display_image_filePath;
                    $params['image'] = $display_image_path_for_db;
                    //Storage::disk('s3')->delete('Profile/DisplayImage/' . end($uri_parts_des));
                }else{
                    $params['image'] = $params['oldOdisplayImg'];
                }

                $user->name = $params['name'];
                $user->email = $params['email'];
                $user->phone_number = $params['phone_number'];
                $user->user_role = $params['user_role'];
                $user->state = $params['state'];
                $user->image = $params['image'];

                if($user->save()){
                    return redirect('/admin/users')->with('success','Update User info successfully!');
                }else{
                    return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
                }
            }else{
                return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
            }
        }
    }

    public function activeInActiveUser(Request $request)
    {
        $perameter = $request->all();
        $validation = Validator::make($perameter,[
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $user = User::find($perameter['id']);
            if($user){
                if($perameter['status'] == 1){
                    $user->is_active = 1;
                    if($user->save()){
                        $message = array('success'=>true,'message'=>'Activate successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }else{
                    $user->is_active = 0;
                    if($user->save()){
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

    public function deleteUser(Request $request)
    {
        $perameter = $request->all();
        $validation = Validator::make($perameter,[
            'id' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $user = User::find($perameter['id']);
            if($user){
                $params['oldOdisplayImg'] = $user->image;
                if($user->delete()){
                    $uri_parts_des = explode('/', $params['oldOdisplayImg']);
                    Storage::disk('s3')->delete('Profile/DisplayImage/' . end($uri_parts_des));
                    $message = array('success'=>true,'message'=>'Delete successfully.');
                    return json_encode($message);
                }else{
                    $message = array('success'=>false,'message'=>'Somthing went wrong!');
                    return json_encode($message);
                }
            }
        }
    }

}
