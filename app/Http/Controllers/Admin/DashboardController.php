<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MobileBrands;
use App\Models\MobileModels;
use App\Models\Sellers;
use App\Models\DeliveryPartners;
use App\Models\OrderInvoices;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data['user'] = User::count();
        $data['total_mobile_brands']  = MobileBrands::count();
        $data['total_mobile_models'] = MobileModels::count();

        $data['total_delivery_partners'] = User::where('user_role',8)->count();
        $data['total_sellers'] = Sellers::count();
        $data['total_invoices'] = OrderInvoices::count();
        
        return view('Admin.dashboard',['data'=>$data]);
    }
}
