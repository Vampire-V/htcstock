<?php

use App\Enum\UserEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('slug', UserEnum::SUPERADMIN)->first();
        $authorRole = Role::where('slug', UserEnum::ADMINIT)->first();
        $userRole = Role::where('slug', UserEnum::USERIT)->first();
        // $response = Http::get(ENV('USERS_INFO'));

        $users = User::all();
        if ($users) {
            foreach ($users as $key => $item) {
                if ($item->email === 'pipat.p@haier.co.th') {
                    $item->roles()->attach($adminRole);
                    $item->roles()->attach($authorRole);
                }
                if ($item->email === 'tanapat.k@haier.co.th') {
                    $item->roles()->attach($authorRole);
                }
                $item->roles()->attach($userRole);
            }
        }

        $per = Permission::all();
        if ($per) {
            foreach ($per as $key => $value) {
                if (substr($value->slug, 0, 6) !== 'delete') {
                    $authorRole->permissions()->attach($value);
                    $userRole->permissions()->attach($value);
                }
                $adminRole->permissions()->attach($value);
            }
        }
    }
}
