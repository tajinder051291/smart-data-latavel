<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\User;
use App\Models\DeliveryPartners;
use App\Models\UserRoles;
use File,URL,Mail;
use App\CommonHelpers;
use Illuminate\Support\Facades\Storage;

class DeliveryPartnersController extends Controller
{
	public function deliveryPartners(Request $request)
    {
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $deliverPartners  = User::where('user_role','8')
                                ->where('name','LIKE',"%{$search}%")
                                ->orWhere('phone_number','LIKE',"%{$search}%")
                                ->orWhere('country_code','LIKE',"%{$search}%")                                                
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        }else{
            $deliverPartners = User::where('user_role','8')->orderBy('created_at', 'desc')->paginate(10);
        }
        return view('Admin.DeliveryPartners.deliveryPartners',['deliverPartners'=>$deliverPartners,'user'=>\Auth::user()]);
    }

    public function addDeliveryPartnersForm(Request $request){
        return view('Admin.DeliveryPartners.addDeliveryPartners');
    }

    public function addDeliveryPartners(Request $request)
    {
        $user = \Auth::user();
        $is_admin = \Auth::guard('admin')->check();


        $params = $request->all();
        $validation = Validator::make($params,[
            // 'name' => 'required|unique:delivery_partners,name',
            // 'phone_number' => 'required|unique:delivery_partners,phone_number',
            // 'email' => 'required|unique:delivery_partners,email',

            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{

            if ($request->hasFile('display_image')) {
                $file = $request->file('display_image');
                $fileext    = $file->getClientOriginalExtension();
                $name = uniqid().'-'.time().'.'.$fileext;
                $filePath = 'Profile/' . $name;
                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
                $params['image'] = $image_path_for_db;
            }
            
            $params['added_by'] = $user->id;
            $params['added_by_role'] = $is_admin ? 0 : $user->user_role;

            $params['user_role'] = 8;

            $password = CommonHelpers::generateOtp(6);
            $params['password'] = bcrypt($password);

            if( $user->user_role == 0 || $user->user_role == 6  ){
                $params['is_active'] = 1;
            }else{
                $params['is_active'] = 0;
            }

            // $deliveryPartner = DeliveryPartners::create($params);
            $deliveryPartner = User::create($params);
            
            if($deliveryPartner){

                $userRole = UserRoles::where('id',8)->first();
                
                $params['password'] = $password;
                $params['phone'] = $deliveryPartner->phone_number;
                $params['email'] = $deliveryPartner->email;
                $params['role'] = $userRole->name;
                
                Mail::send('emails.email_credentials', [ 'user' => $params ,'msg'=>'Please use these credentials to access the apps' ],
                    function ($m) use ($deliveryPartner){
                    $m->from(config('mail.from.address'),config('app.name'));
                    $m->to($deliveryPartner->email)->subject('Smartbiz Login Details');
                });

                return redirect('/admin/delivery-partners')->withInput()->with('success','Delivery Partner added successfully.');
            }else{
                return redirect()->back()->withInput()->with('error','Something went wrong!');
            }            
        }
    }

    public function editDeliveryPartnersForm(Request $request,$id){

        $id = base64_decode($id);
        $deliveryPartner = User::find($id);
        return view('Admin.DeliveryPartners.editDeliveryPartners',['deliveryPartner'=> $deliveryPartner]);
    
    }

    public function editDeliveryPartners(Request $request)
    {
        $user = \Auth::user();
        $params = $request->all();
        $validation = Validator::make($params,[
            // 'name' => 'required|unique:delivery_partners,name,'.$params['id'],
            // 'phone_number' => 'required|unique:delivery_partners,phone_number,'.$params['id'],
            // 'email' => 'required|unique:delivery_partners,email,'.$params['id']

            'user_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$params['user_id'],
            'phone_number' => 'required|unique:users,phone_number,'.$params['user_id'],
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            
            $deliveryPartner = User::find($params['user_id']);
            if($deliveryPartner){

                $uri_parts_des = explode('/', $params['oldOdisplayImg']);
                if ($request->hasFile('display_image')) {
                    $image = $request->file('display_image');
                    $fileext    = $image->getClientOriginalExtension();
                    $display_image_name = uniqid().'-'.time().'.'.$fileext;
                    $display_image_filePath = 'Profile/DisplayImage/' . $display_image_name;
                    $image_url = Storage::disk('s3')->put($display_image_filePath, file_get_contents($image),'public');
                    $display_image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$display_image_filePath;
                    $params['image'] = $display_image_path_for_db;
                    //Storage::disk('s3')->delete('Profile/DisplayImage/' . end($uri_parts_des));
                }else{
                    $params['image'] = $params['oldOdisplayImg'];
                }

                $deliveryPartner->name = trim($params['name']);
                $deliveryPartner->phone_number = trim($params['phone_number']);
                $deliveryPartner->image = trim($params['image']);
                $deliveryPartner->email = trim($params['email']);
                $deliveryPartner->address = trim($params['address']);
                $deliveryPartner->company_name = trim($params['company_name']);

                if($deliveryPartner->save()){
                    return redirect('/admin/delivery-partners')->with('success','Updated Delivery Partner info successfully!');
                }else{
                    return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
                }
            }else{
                return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
            }
        }
    }

    
    public function activeInActiveDeliveryPartners(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            
            $allowed = (\Auth::user()->user_role == 6) || \Auth::guard('admin')->check();

            //only accounts or super admins  can verify
            if( ! $allowed ){
                $message = array('success'=>false,'message'=>'Permission denied !');
                return json_encode($message);
            }

            $deliveryPartner = User::find($params['id']);
            if($deliveryPartner){
                if($params['status'] == 1){
                    $deliveryPartner->is_active = 1;
                    if($deliveryPartner->save()){
                        $message = array('success'=>true,'message'=>'Activate successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }else{
                    $deliveryPartner->is_active = 0;
                    if($deliveryPartner->save()){
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

    public function deleteDeliveryPartners(Request $request)
    {
        $perameter = $request->all();
        $validation = Validator::make($perameter,[
            'id' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $deliveryPartner = DeliveryPartners::find($perameter['id']);
            if($deliveryPartner){
                $params['oldOdisplayImg'] = $deliveryPartner->image;
                if($deliveryPartner->delete()){
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