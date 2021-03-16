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
            ['name' => 'January', 'year' => date('Y')],
            ['name' => 'February', 'year' => date('Y')],
            ['name' => 'March', 'year' => date('Y')],
            ['name' => 'April', 'year' => date('Y')],
            ['name' => 'May', 'year' => date('Y')],
            ['name' => 'June', 'year' => date('Y')],
            ['name' => 'July', 'year' => date('Y')],
            ['name' => 'August', 'year' => date('Y')],
            ['name' => 'September', 'year' => date('Y')],
            ['name' => 'October', 'year' => date('Y')],
            ['name' => 'November', 'year' => date('Y')],
            ['name' => 'December', 'year' => date('Y')],
        ];
        foreach ($month as $key => $value) {
            TargetPeriod::firstOrCreate($value);
        }
    }
}
