<?php

use App\Models\KPI\TargetPeriod;
use Illuminate\Database\Seeder;

class TargetPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $month = [
            ['name' => '01', 'year' => date('Y')],
            ['name' => '02', 'year' => date('Y')],
            ['name' => '03', 'year' => date('Y')],
            ['name' => '04', 'year' => date('Y')],
            ['name' => '05', 'year' => date('Y')],
            ['name' => '06', 'year' => date('Y')],
            ['name' => '07', 'year' => date('Y')],
            ['name' => '08', 'year' => date('Y')],
            ['name' => '09', 'year' => date('Y')],
            ['name' => '10', 'year' => date('Y')],
            ['name' => '11', 'year' => date('Y')],
            ['name' => '12', 'year' => date('Y')],
        ];
        foreach ($month as $key => $value) {
            TargetPeriod::firstOrCreate($value);
        }
    }
}
