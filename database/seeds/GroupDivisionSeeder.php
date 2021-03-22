<?php

use App\Models\GroupDivision;
use Illuminate\Database\Seeder;

class GroupDivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Gdivisions = [
            ['GDivisionID' => '01', 'GDivisionDesc' => 'Platform'],
            ['GDivisionID' => '02', 'GDivisionDesc' => 'RF Haier'],
            ['GDivisionID' => '03', 'GDivisionDesc' => 'AC Microenterprise'],
            ['GDivisionID' => '04', 'GDivisionDesc' => 'Spare'],
            ['GDivisionID' => '05', 'GDivisionDesc' => 'Window Type AC Microenterprise'],
            ['GDivisionID' => '06', 'GDivisionDesc' => 'Support Platform RF'],
            ['GDivisionID' => '07', 'GDivisionDesc' => 'Production Platform  RF'],
            ['GDivisionID' => '08', 'GDivisionDesc' => 'RF AQUA'],
            ['GDivisionID' => '09', 'GDivisionDesc' => 'Technical Platform'],
            ['GDivisionID' => '10', 'GDivisionDesc' => 'Support Platform AC'],
            ['GDivisionID' => '11', 'GDivisionDesc' => 'SAC  Micro'],
            ['GDivisionID' => '12', 'GDivisionDesc' => 'Production Platform  AC'],
            ['GDivisionID' => '13', 'GDivisionDesc' => 'WAC Micro'],
            ['GDivisionID' => '14', 'GDivisionDesc' => 'Technical Platform AC'],
        ];

        foreach ($Gdivisions as $key => $value) {
            GroupDivision::firstOrCreate($value);
        }
    }
}
