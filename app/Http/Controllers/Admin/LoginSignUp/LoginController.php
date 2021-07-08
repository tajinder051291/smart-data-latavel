<?php

namespace App\Http\Controllers\Admin\LoginSignUp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth, URL, DB, Mail;
use App\Models\Admin;
use App\Models\User;
use App\Utils\Roles;
use App\CommonHelpers;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        return view('Admin.LoginSignUp.login');
    }
    public function login(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ( $validation->fails() ) {
            return redirect()->back()->with('error',$validation->messages()->first());
        }else{
            $dataCheck = array('email' => $params['email'],'password' => $params['password']);
            $admin = Admin::where('email',$params['email'])->first();
            if($admin){

                if(Auth::guard('admin')->attempt($dataCheck)){
                    //Auth::guard('manager')->logout();
                    //dd(Auth::guard('admin')->check());
                    return redirect('/admin/dashboard')->with('success','Loged in successfully!');
                }else{
                    return redirect()->back()->with('error','Please enter valid credentials!');
                }
            }else{

                $user = User::where('email',$params['email'])->where('is_active',1)->first();
                if($user){
                    if(Auth::guard('manager')->attempt($dataCheck)){
                        if($user->user_role=='1' || $user->user_role=='2' ||  $user->user_role=='6'){
                           //Auth::guard('admin')->logout();
                            //dd(Auth::guard('manager')->check());
                            //dd(Auth::guard('admin')->check());
                            return redirect('/admin/dashboard')->with('success','Loged in successfully!'); 
                        }
                        return redirect()->back()->with('error','You are not authorised to access');
                    }else{
                        return redirect()->back()->with('error','Please enter valid credentials!');
                    }
                }
                return redirect()->back()->with('error','Account is not found!');
            }
        }
    }

    public function logOut(Request $request){
        Auth::guard('admin')->logout();
        Auth::guard('manager')->logout();
        return redirect()->back();
    }

    public function logOutManager(Request $request){
        Auth::guard('manager')->logout();
        return redirect()->back();
    }

    public function forgetPasswordForm(Request $request){
        return view('Admin.LoginSignUp.forgetpassword');
    }

    public function forgetpassword(Request $request){
        $params = $request->all();
        $validation = Validator::make($params, [
            'email' => 'required|email'
        ]);
        if ( $validation->fails() ) {
            return redirect()->back()->with('error',$validation->messages()->first());
        }else{
            $admin = Admin::where('email',$params['email'])->first();
            if($admin){
                
                DB::table('password_resets')->where('email', $params['email'])->delete();

                $token = CommonHelpers::randomString(64);
                $date = date("Y-m-d H:i:s");

                DB::table('password_resets')->insert([
                    'email' => $params['email'],
                    'token' => $token,
                    'created_at' => $date
                ]);

                $link = config('app.url') . 'password/reset/' . $token . '?email=' . urlencode($params['email']);

                $data['URL'] = $link;

                Mail::send('emails.forgetPassword', [ 'data' => $data ,'msg'=>'Forget Password' ],
                        function ($m) use ($admin){
                        $m->from(config('mail.from.address'),config('app.name'));
                        $m->to($admin->email)->subject('Smartbiz Forget Password');
                });

                return redirect()->back()->with('success', trans('A reset link has been sent to your email address.'));

            }else{
                return redirect()->back()->with('error','Account is not found!');
            }
        }
    }

    public function resetpassword(Request $request){
        //$params = $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|confirmed',
            'token' => 'required|exists:password_resets,token' 
        ]);
    
        if ( $validator->fails() ) {
            return redirect()->back()->with('error',$validator->messages()->first());
        }else{
            $tokenData = DB::table('password_resets')->where('token', $request->token)->first();
            if($tokenData){
                $admin = Admin::where('email',$tokenData->email)->first();
                if($admin){
                    $admin->password = bcrypt($request->password);
                    $admin->save();
                    $tokenData = DB::table('password_resets')->where('token', $request->token)->delete();
                    //return redirect('/')->with('success','Password changed successfully!');
                    return redirect('/');
                }else{
                    return redirect()->back()->with('error','Invalid Admin');
                }
            }else{
                return redirect()->back()->with('error','Invalid Token');
            }
        }
    }

}
