<?php

use App\Enum\UserEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
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
                'name' => 'Super Admin',
                'slug' => UserEnum::SUPERADMIN,
            ],
            [
                'name' => 'Admin IT Stock',
                'slug' => UserEnum::ADMINIT,
            ],
            [
                'name' => 'User IT Stock',
                'slug' => UserEnum::USERIT,
            ],
            [
                'name' => 'Admin Legal',
                'slug' => UserEnum::ADMINLEGAL,
            ],
            [
                'name' => 'User Legal',
                'slug' => UserEnum::USERLEGAL,
            ],
            [
                'name' => 'Admin KPI',
                'slug' => UserEnum::ADMINKPI,
            ],
            [
                'name' => 'User KPI',
                'slug' => UserEnum::USERKPI,
            ],
            [
                'name' => 'Manager KPI',
                'slug' => UserEnum::MANAGERKPI,
            ],
        ];

        foreach ($roles as $key => $value) {
            Role::firstOrCreate($value);
        }
    }
}
