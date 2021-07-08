<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class UserExport implements FromView
{
    function __construct( int $id){
        $this->id = $id;
    }
   
    public function view(): View
    {
        // dd(User::with('orders')->whereId($this->id)->first()->toArray());
        return view('Admin.Users.export', [
            'user' => User::with('orders')->whereId($this->id)->first()
        ]);
    }
}
