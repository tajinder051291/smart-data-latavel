<?php

namespace App\Http\Controllers\Admin\LoginSignUp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth, URL;
use App\Models\Admin;

class RegisterController extends Controller
{
    public function showRegisterForm(Request $request)
    {
        return view('Admin.LoginSignUp.register');
        // $params = $request->all();
    }

    public function register(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'name' => 'required',
            'email' => 'required|unique:admins,email|email|max:255',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation'
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $params['password'] = bcrypt($params['password']);
            if($user = Admin::create($params)){
                return redirect('/')->with('success','Account registered successfully!');
            }else{
                return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
            }
        } 
    }
}
