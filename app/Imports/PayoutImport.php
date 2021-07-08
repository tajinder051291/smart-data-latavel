<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PayoutImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        return $rows;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // public function model(array $row)
    // {
    //     return $row;
    //     // $user = User::where('phone_number',$row['phone'])->first();
    //     // $approved_points = $user->approved_points;
    //     // return User::where('phone_number',$row['phone'])->update([
    //     //     'approved_points' => $approved_points + $row['payout']
    //     // ]);
    // }
}