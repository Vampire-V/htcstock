<?php

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $division = [
            ['id' => 1, 'name' => 'President', 'division_id' => '002'],
            ['id' => 2, 'name' => 'Human Resource & General Admin', 'division_id' => '004'],
            ['id' => 3, 'name' => 'Finance & Accounting', 'division_id' => '005'],
            ['id' => 4, 'name' => 'Strategy & Operation', 'division_id' => '003'],
            ['id' => 5, 'name' => 'Marketing', 'division_id' => '006'],
            ['id' => 6, 'name' => 'Supply Chain Management', 'division_id' => '007'],
            ['id' => 7, 'name' => 'Utility', 'division_id' => '008'],
            ['id' => 8, 'name' => 'Support Platform RF', 'division_id' => '009'],
            ['id' => 9, 'name' => 'RF Haier', 'division_id' => '035'],
            ['id' => 10, 'name' => 'RF AQUA', 'division_id' => '044'],
            ['id' => 11, 'name' => 'Production Platform RF', 'division_id' => '041'],
            ['id' => 12, 'name' => 'Technical Platform RF', 'division_id' => '050'],
            ['id' => 13, 'name' => 'Support Platform AC', 'division_id' => '054'],
            ['id' => 14, 'name' => 'SAC', 'division_id' => null],
            ['id' => 15, 'name' => 'Production Platform AC', 'division_id' => '061'],
            ['id' => 16, 'name' => 'Technical Platform AC', 'division_id' => '065'],
            ['id' => 17, 'name' => 'WAC', 'division_id' => null],
        ];

        foreach ($division as $key => $value) {
            Division::firstOrCreate($value);
        }
    }
}
