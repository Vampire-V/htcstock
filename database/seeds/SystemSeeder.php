<?php

use App\Models\System;
use App\Models\User;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systems = [
            [
                'name' => 'IT Stock',
                'slug' => 'it',
                'icon' => 'fa fa-dropbox fa-5x'
            ],
            [
                'name' => 'Contract Legal',
                'slug' => 'legal',
                'icon' => 'fa fa-gavel fa-5x'
            ],
            [
                'name' => 'Haier KPI',
                'slug' => 'kpi',
                'icon' => 'fa fa-bar-chart fa-5x'
            ]
        ];

        foreach ($systems as $key => $value) {
            System::firstOrCreate($value);
        }
        $it = System::where('slug','it')->first();
        $users = User::all();
        foreach ($users as $key => $user) {
            $user->systems()->attach($it);
        }
    }
}
