<?php

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
                'slug' => 'super-admin',
            ],
            [
                'name' => 'Admin IT Stock',
                'slug' => 'admin-it',
            ],
            [
                'name' => 'User IT Stock',
                'slug' => 'user-it',
            ],
            [
                'name' => 'Admin Legal',
                'slug' => 'admin-legal',
            ],
            [
                'name' => 'User Legal',
                'slug' => 'user-legal',
            ],
            [
                'name' => 'Admin KPI',
                'slug' => 'admin-kpi',
            ],
            [
                'name' => 'User KPI',
                'slug' => 'user-kpi',
            ],
        ];

        foreach ($roles as $key => $value) {
            Role::firstOrCreate($value);
        }
    }
}
