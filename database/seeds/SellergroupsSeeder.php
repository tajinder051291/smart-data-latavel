<?php

use Illuminate\Database\Seeder;
use App\Models\SellerGroups;
use Carbon\Carbon;

class SellergroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
                    [
                        'id'         => 1,
                        'title'      => 'Gold',
                        'image'      => null,
                        'is_active'  => 1,                        
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ],
                    [
                        'id'         => 2,
                        'title'      => 'Silver',
                        'image'      => null,
                        'is_active'  => 1,                        
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ],
                    [   'id'          => 3,
                        'title'      => 'Bronze',
                        'image'      => null,
                        'is_active'  => 1,                        
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ]
                ];

        // SellerGroups::insert($groups);
    }
}
