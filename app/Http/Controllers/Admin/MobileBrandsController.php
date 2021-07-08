<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\MobileBrands;
use App\Models\MobileModels;
use App\Models\AppPermissions;
use File,URL;
use Illuminate\Support\Facades\Storage;

class MobileBrandsController extends Controller
{

    public function mobileBrandList(Request $request)
    {
        if($request->has('search') && $request->search != ''){
            $search = trim($request->search);
            $mobileBrand  = MobileBrands::where('brand_name','LIKE',"%{$search}%")->orderBy('brand_name', 'asc')->paginate(10);
        }else{
            $mobileBrand = MobileBrands::orderBy('brand_name', 'asc')->paginate(10);
        }
        return view('Admin.MobileBrands.mobileBrands',['mobileBrands'=>$mobileBrand]);
    }

    public function addMobileBrandsForm(Request $request){
        return view('Admin.MobileBrands.addMobileBrands');
    }

    public function addMobileBrands(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'brand_name' => 'required|unique:mobile_brands,brand_name'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $params['brand_name'] = trim($params['brand_name']);
            $mobileBrand = MobileBrands::create($params);
            if($mobileBrand){
                return redirect('/admin/mobile-brands')->withInput()->with('success','Mobile Brand added successfully.');
            }else{
                return redirect()->back()->withInput()->with('error','Somthing went wrong!');
            }
        }
    }

    public function editMobileBrandsForm(Request $request,$id)
    {
        $id = base64_decode($id);
        $mobileBrand = MobileBrands::find($id);
        return view('Admin.MobileBrands.editMobileBrands',['mobileBrand'=> $mobileBrand]);
    }

    public function editMobileBrands(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'brand_name' => 'required|unique:mobile_brands,brand_name,'.$params['id']
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $mobileBrand = MobileBrands::find($params['id']);
            if($mobileBrand){
                $mobileBrand->brand_name = trim($params['brand_name']);
                if($mobileBrand->save()){
                    return redirect('/admin/mobile-brands')->with('success','Update Mobile brand info successfully!');
                }else{
                    return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
                }
            }else{
                return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
            }
        }
    }

    public function deleteMobileBrand(Request $request){

        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $mobileBrand = MobileBrands::find($params['id']);
            if($mobileBrand){
                if($mobileBrand->delete()){
                    $message = array('success'=>true,'message'=>'Delete successfully.');
                    return json_encode($message);
                }else{
                    $message = array('success'=>false,'message'=>'Somthing went wrong!');
                    return json_encode($message);
                }
            }
        }
    } 

    public function activeInActiveBrand(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $brands = MobileBrands::find($params['id']);

            if($brands){
                if($params['status'] == 1){
                    $brands->is_active = 1;
                    if($brands->save()){
                        $message = array('success'=>true,'message'=>'Activate successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }else{
                    $brands->is_active = 0;
                    if($brands->save()){
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

    public function mobileModelList(Request $request,$brandId)
    {
        $id = base64_decode($brandId);
        $brand = MobileBrands::find($id);
        
        if($request->has('search') && $request->search != ''){
            $search = trim($request->search);
            $mobileModel  = MobileModels::where('model','LIKE',"%{$search}%")->paginate(10);
        }else{
            $mobileModel = MobileModels::where('brand_id',$id)->orderBy('created_at', 'desc')->paginate(10);
        }
        return view('Admin.MobileModels.mobileModels',['mobileModels'=>$mobileModel,'brandId'=>$id,'brand'=>$brand]);
    } 

    public function addMobileModelForm(Request $request,$id){
        return view('Admin.MobileModels.addMobileModels',['brandId'=>$id]);
    } 

    public function addMobileModels(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            // 'model'   => 'required|unique:mobile_models,model',
            'model'   => 'required',
            'color'     => 'required',
            'specification' => 'required'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
           // $params['ram'] = trim($params['ram']);
            $params['model'] = trim($params['model']);
            $params['color'] = trim($params['color']);
            $params['specification'] = trim($params['specification']);
            $ramStorage = explode('/', $params['specification']);
            if(count($ramStorage)>0){
                $params['ram'] = trim($ramStorage[0]);
                $params['storage'] = trim($ramStorage[1]);
            }
           // $params['storage'] = trim($params['storage']);
            $mobileModel = MobileModels::create($params);
            if($mobileModel){
                return redirect('/admin/modellisting/'.base64_encode($params['brand_id']))->withInput()->with('success','Mobile Model added successfully.');
            }else{
                return redirect()->back()->withInput()->with('error','Somthing went wrong!');
            }
        }
    }

    public function editMobileModelsForm(Request $request,$brand_id,$id)
    {
        $brandId = base64_decode($brand_id);
        $id = base64_decode($id);
        $mobileModel = MobileModels::find($id);
        return view('Admin.MobileModels.editMobileModels',['mobileModel'=> $mobileModel]);
    }

    public function editMobileModels(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            // 'model'   => 'required|unique:mobile_models,model,'.$params['id'],
            'model'   => 'required',
            'specification'     => 'required',
            'color' => 'required'
        ]);
        if($validation->fails()){
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $mobileModel = MobileModels::find($params['id']);
            if($mobileModel){

                $mobileModel->model = trim($params['model']);
                $mobileModel->color = trim($params['color']);
                $mobileModel->specification = trim($params['specification']);
                $ramStorage = explode('/', $params['specification']);
                if(count($ramStorage)>0){
                    $mobileModel->ram = trim($ramStorage[0]);
                    $mobileModel->storage = trim($ramStorage[1]);
                }

               // $mobileModel->ram = trim($params['ram']);
               // $mobileModel->model = trim($params['model']);
               // $mobileModel->storage = trim($params['storage']);

                if($mobileModel->save()){
                    $brandId=base64_encode($mobileModel->brand_id);
                    return redirect('/admin/modellisting/'.$brandId)->with('success','Update Mobile Model info successfully!');
                }else{
                    return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
                }
            }else{
                return redirect()->back()->withInput()->with('error','Oops! Something went wrong. Try some time later.');
            }
        }
    }  

    public function activeInActiveModel(Request $request)
    {
        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $mobileModel = MobileModels::find($params['id']);

            if($mobileModel){
                if($params['status'] == 1){
                    $mobileModel->is_active = 1;
                    if($mobileModel->save()){
                        $message = array('success'=>true,'message'=>'Activate successfully.');
                        return json_encode($message);
                    }else{
                        $message = array('success'=>false,'message'=>'Somthing went wrong!');
                        return json_encode($message);
                    }
                }else{
                    $mobileModel->is_active = 0;
                    if($mobileModel->save()){
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

    public function deleteMobileModel(Request $request){

        $params = $request->all();
        $validation = Validator::make($params,[
            'id' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->with('error',$validation->messages()->first());
        }else{
            $mobileModel = MobileModels::find($params['id']);
            if($mobileModel){
                if($mobileModel->delete()){
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
