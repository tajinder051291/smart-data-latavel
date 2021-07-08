<?php

use Illuminate\Database\Seeder;
use App\Models\UserRoles;
use Carbon\Carbon;

class RolesSeeder extends Seeder
{
     /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
                    [
                        'id'         => 1,
                        'name'       => 'Fortbell',
                        'app_permission_id'=> null,
                        'is_active'  => 1,
                        'type'  => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ],
                    [
                        'id'         => 2,
                        'name'       => 'Buyer',
                        'app_permission_id'=> null,
                        'is_active'  => 1,
                        'type'  => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ],
                    [
                        'id'         => 3,
                        'name'       => 'Warehouse',
                        'app_permission_id'=> null,
                        'is_active'  => 1,
                        'type'  => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ],
                    [
                        'id'         => 4,
                        'name'       => 'Logistics',
                        'app_permission_id'=> null,
                        'is_active'  => 1,
                        'type'  => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ],
                    [
                        'id'         => 5,
                        'name'       => 'Fright Forward',
                        'app_permission_id'=> null,
                        'is_active'  => 1,
                        'type'  => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ],
                    [
                        'id'         => 6,
                        'name'       => 'Accounts',
                        'app_permission_id'=> null,
                        'is_active'  => 1,
                        'type'  => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ],
                    [
                        'id'         => 7,
                        'name'       => 'Seller',
                        'app_permission_id'=> null,
                        'is_active'  => 1,
                        'type'  => 2,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ],
                    [
                        'id'         => 8,
                        'name'       => 'Delivery Partner',
                        'app_permission_id'=> null,
                        'is_active'  => 1,
                        'type'  => 3,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'deleted_at' => null,
                    ]
                ];

        UserRoles::insert($roles);
    }
}

