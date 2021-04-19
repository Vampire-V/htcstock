<?php

use App\Models\KPI\KpiRuleType;
use Illuminate\Database\Seeder;

class RuleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = [
            ['name' => 'finance'],
            ['name' => 'market'],
            ['name' => 'operation']
        ];
        foreach ($type as $value) {
            KpiRuleType::updateOrCreate($value,['name' => $value["name"]]);
        }
    }
}
