<?php

namespace App\Exports;

use App\Models\Sellers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SellerExport implements FromView
{
    function __construct( int $id){
        $this->id = $id;
    }
   
    public function view(): View
    {
        // dd(Sellers::with('orders')->whereId($this->id)->first()->toArray());
        return view('Admin.Sellers.export', [
            'seller' => Sellers::with('orders')->whereId($this->id)->first()
        ]);
    }
}
