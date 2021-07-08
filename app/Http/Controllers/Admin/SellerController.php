<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\Sellers;
use App\Models\Orders;
use App\Models\OrderInvoices;
use File,URL,Mail;
use Illuminate\Support\Facades\Storage;
use App\CommonHelpers;

use App\Exports\SellerExport;
use Maatwebsite\Excel\Facades\Excel;

class SellerController extends Controller
{
	public function sellersList(Request $request, $type)
    {
    	if($type=='1'){ // all
    		$sellers = Sellers::orderBy('created_at', 'desc')->paginate(10);
        }else if($type=='2'){ // Approved
        	$sellers = Sellers::where('is_verified',1)->orderBy('created_at', 'desc')->paginate(10);
        }else{ // Pending
        	$sellers = Sellers::where('is_verified',0)->orderBy('created_at', 'desc')->paginate(10);
        }

    	if($request->has('search') && $request->search != ''){
            $search = $request->search;
            if($type=='1'){ // all
	    		$sellers = Sellers::where('name','LIKE',"%{$search}%")
                            ->orWhere('email','LIKE',"%{$search}%")
                            ->orWhere('phone_number','LIKE',"%{$search}%")
                            ->orWhere('address','LIKE',"%{$search}%")
                            ->orWhere('pincode','LIKE',"%{$search}%")
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
	        }else if($type=='2'){ // Approved
	        	$sellers = Sellers::where('name','LIKE',"%{$search}%")
                            ->orWhere('email','LIKE',"%{$search}%")
                            ->orWhere('phone_number','LIKE',"%{$search}%")
                            ->orWhere('address','LIKE',"%{$search}%")
                            ->orWhere('pincode','LIKE',"%{$search}%")
                            ->where('is_verified',1)->orderBy('created_at', 'desc')->paginate(10);
	        }else{ // Pending
	        	$sellers = Sellers::where('name','LIKE',"%{$search}%")
                            	->orWhere('email','LIKE',"%{$search}%")
                            	->orWhere('phone_number','LIKE',"%{$search}%")
                            	->orWhere('address','LIKE',"%{$search}%")
                            	->orWhere('pincode','LIKE',"%{$search}%")
                            	->where('is_verified',0)->orderBy('created_at', 'desc')->paginate(10);
	        }
        }else{
            if($type=='1'){ // all
	    		$sellers = Sellers::orderBy('created_at', 'desc')->paginate(10);
	        }else if($type=='2'){ // Approved
	        	$sellers = Sellers::where('is_verified',1)->orderBy('created_at', 'desc')->paginate(10);
	        }else{ // Pending
	        	$sellers = Sellers::where('is_verified',0)->orderBy('created_at', 'desc')->paginate(10);
	        }
        }

        return view('Admin.Sellers.sellers',['sellers'=>$sellers,'user'=>\Auth::user()]);
    }

    public function addSellerForm(Request $request){
    	return view('Admin.Sellers.addSellers');
    }

    public function addSellers(Request $request)
    {
        $user = \Auth::user();
        $is_admin = \Auth::guard('admin')->check();
        // dd($user,1);

        $params = $request->all();
        $validation = Validator::make($params,[
            'name' => 'required',
            'email' => 'required|unique:sellers,email',
            'phone_number' => 'required|unique:sellers,phone_number',
            'pincode'  => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{

        	if ($request->hasFile('aadhaar_front_image')) {
                $file = $request->file('aadhaar_front_image');
                $fileext    = $file->getClientOriginalExtension();
                $name = uniqid().'-'.time().'.'.$fileext;
                $filePath = 'Sellers/' . $name;
                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
                $params['aadhaar_front_image'] = $image_path_for_db;
            }

            if ($request->hasFile('aadhaar_back_image')) {
                $file = $request->file('aadhaar_back_image');
                $fileext    = $file->getClientOriginalExtension();
                $name = uniqid().'-'.time().'.'.$fileext;
                $filePath = 'Sellers/' . $name;
                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
                $params['aadhaar_back_image'] = $image_path_for_db;
            }

            if ($request->hasFile('pan_image')) {
                $file = $request->file('pan_image');
                $fileext    = $file->getClientOriginalExtension();
                $name = uniqid().'-'.time().'.'.$fileext;
                $filePath = 'Sellers/' . $name;
                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
                $params['pan_image'] = $image_path_for_db;
            }

            if ($request->hasFile('gst_image')) {
                $file = $request->file('gst_image');
                $fileext    = $file->getClientOriginalExtension();
                $name = uniqid().'-'.time().'.'.$fileext;
                $filePath = 'Sellers/' . $name;
                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
                $params['gst_image'] = $image_path_for_db;
            }

            if ($request->hasFile('cheque_image')) {
                $file = $request->file('cheque_image');
                $fileext    = $file->getClientOriginalExtension();
                $name = uniqid().'-'.time().'.'.$fileext;
                $filePath = 'Profile/' . $name;
                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
                $params['cheque_image'] = $image_path_for_db;
            }

            $params['name'] = trim($params['name']);
            $params['email'] = trim($params['email']);
            $params['phone_number'] = trim($params['phone_number']);
            $params['address'] = trim($params['address']);
            $params['pincode'] = trim($params['pincode']);
            $params['is_verified'] = 0;
            $params['is_active'] = 0;


            if( $user->user_role == 6 ){
                $params['is_verified'] = 1;
                $params['is_active'] = 1;
            }

            $password = CommonHelpers::generateOtp(6);
            $params['password'] = bcrypt($password);


            $params['added_by'] = $user->id;
            $params['added_by_role'] = $is_admin ? 0 : $user->user_role;
            
            $seller = Sellers::create($params);
            if($seller){
                
                $params['password'] = $password;
                $params['phone'] = $params['phone_number'];
                Mail::send('emails.email_credentials', [ 'user' => $params ,'msg'=>'Please use these credentials to access the apps.' ],
                        function ($m) use ($seller){
                        $m->from(config('mail.from.address'),config('app.name'));
                        $m->to($seller->email)->subject('Smartbiz Login Details');
                });

                return redirect('/admin/sellers/1')->withInput()->with('success','Seller added successfully.');
            }else{
                return redirect()->back()->withInput()->with('error','Somthing went wrong!');
            }
        }
    }

    public function editSellersForm(Request $request,$id)
    {
        $id = base64_decode($id);
        $seller = Sellers::find($id);
        return view('Admin.Sellers.editSellers',['seller'=> $seller]);
    }

    public function editSellers(Request $request)
    {
        $user = \Auth::user();
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:sellers,email,'.$params['id'],
            'phone_number' => 'required|unique:sellers,phone_number,'.$params['id'],
            'address' => 'required',
            'pincode' => 'required'
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            
            $seller = Sellers::find($params['id']);
            if($seller){

                if ($request->hasFile('aadhaar_front_image')) {
                    $file = $request->file('aadhaar_front_image');
                    $fileext    = $file->getClientOriginalExtension();
                    $name = uniqid().'-'.time().'.'.$fileext;
                    $filePath = 'Sellers/' . $name;
                    $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                    $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
                    $seller->aadhaar_front_image = $image_path_for_db;
                }

                if ($request->hasFile('aadhaar_back_image')) {
                    $file = $request->file('aadhaar_back_image');
                    $fileext    = $file->getClientOriginalExtension();
                    $name = uniqid().'-'.time().'.'.$fileext;
                    $filePath = 'Sellers/' . $name;
                    $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                    $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
                    $seller->aadhaar_back_image = $image_path_for_db;
                }


	            if ($request->hasFile('pan_image')) {
	                $file = $request->file('pan_image');
	                $fileext    = $file->getClientOriginalExtension();
	                $name = uniqid().'-'.time().'.'.$fileext;
	                $filePath = 'Sellers/' . $name;
	                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
	                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
	                $seller->pan_image = $image_path_for_db;
	            }

	            if ($request->hasFile('gst_image')) {
	                $file = $request->file('gst_image');
	                $fileext    = $file->getClientOriginalExtension();
	                $name = uniqid().'-'.time().'.'.$fileext;
	                $filePath = 'Sellers/' . $name;
	                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
	                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
	                $seller->gst_image = $image_path_for_db;
	            }

	            if ($request->hasFile('cheque_image')) {
	                $file = $request->file('cheque_image');
	                $fileext    = $file->getClientOriginalExtension();
	                $name = uniqid().'-'.time().'.'.$fileext;
	                $filePath = 'Profile/' . $name;
	                $image_url = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
	                $image_path_for_db = "https://". env('AWS_BUCKET') .".s3.". env('AWS_DEFAULT_REGION') .".amazonaws.com/".$filePath;
	                $seller->cheque_image = $image_path_for_db;
	            }

	            $seller->name = trim($params['name']);
	            $seller->email = trim($params['email']);
	            $seller->phone_number = trim($params['phone_number']);
	            $seller->address = trim($params['address']);
	            $seller->pincode = trim($params['pincode']);
                
                $seller->aadhaar_number = trim($params['aadhaar_number']);
                $seller->pan_number = trim($params['pan_number']);
                $seller->gst_number = trim($params['gst_number']);
                $seller->check_number = trim($params['check_number']);

                if($seller->save()){
                    return redirect('/admin/sellers/1')->with('success','Update Seller info successfully!');
                }else{
                    return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
                }
            }else{
                return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
            }
        }
    }

    public function activeInActiveSeller(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $seller = Sellers::find($params['id']);
            if($seller){
                if($params['status'] == 1){
                    $seller->is_active = 1;
                    if($seller->save()){
                        $message = array('success'=>true,'message'=>'Activate successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }else{
                    $seller->is_active = 0;
                    if($seller->save()){
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

    public function deleteSeller(Request $request)
    {
        $perameter = $request->all();
        $validation = Validator::make($perameter,[
            'id' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $seller = Sellers::find($perameter['id']);
            if($seller){
                if($seller->delete()){
                    $message = array('success'=>true,'message'=>'Delete successfully.');
                    return json_encode($message);
                }else{
                    $message = array('success'=>false,'message'=>'Somthing went wrong!');
                    return json_encode($message);
                }
            }
        }
    }

    public function verifySeller(Request $request)
    {
        // dd(\Auth::guard('admin')->check());
        $allowed = (\Auth::user()->user_role == 6); 
                        // || \Auth::guard('admin')->check();

        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{

            //only accounts or super admins  can verify
            if( ! $allowed ){
                $message = array('success'=>false,'message'=>'Permission denied !');
                return json_encode($message);
            }

            $seller = Sellers::find($params['id']);
            if($seller){
                if($params['status'] == 1){

                    $password = CommonHelpers::generateOtp(6);

                    $seller->is_verified = 1;
                    $seller->is_active = 1;
                    $seller->password = bcrypt($password);
                    if($seller->save()){

                        $details = array("name"=>$seller->name,"phone"=>$seller->phone_number,"password"=>$password);

                        Mail::send('emails.email_credentials', [ 'user' => $details ,'msg'=>'Please use these credentials to access the apps.' ],
                                function ($m) use ($seller){
                                $m->from(config('mail.from.address'),config('app.name'));
                                $m->to($seller->email)->subject('Smartbiz Login Details');
                        });

                        $message = array('success'=>true,'message'=>'Verified successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }else{
                	$message = array('success'=>false,'message'=>'Somthing went wrong!');
                    return json_encode($message);
                }
            }
        }
    } 

    public function invoicesList(Request $request, $id)
    {
        
        $id = base64_decode($id);
        $seller = Sellers::find($id);

        $orders = Orders::where('order_status','=',1)->where('seller_id',$id)->pluck('id')->toArray();
        $invoices = OrderInvoices::whereIn('order_id',$orders)->paginate();

        return view('Admin.Sellers.invoices',['invoices'=>$invoices,'user'=>\Auth::user()]);
    }



    public function exportSeller( $id )
    {
        $exportSeller = Sellers::findOrFail($id);
        return Excel::download(new SellerExport($id), $exportSeller->name . '.csv');
    }

    
}