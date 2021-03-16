<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\KPI\Evaluate;
use App\Models\KPI\RuleTemplate;
use App\Models\KPI\TargetPeriod;
use Faker\Generator as Faker;
use App\Models\User;

$users = User::all('id');
$periods = TargetPeriod::all('id');
$ruleTemplate = RuleTemplate::all('id');
$factory->define(Evaluate::class, function (Faker $faker) use ($users, $periods, $ruleTemplate) {
    // dd('user_id : ' .$faker->randomElement($users) . ' ' .
    //     'period_id : ' . $faker->randomElement($periods) . ' ' .
    //     'head_id : ' . $faker->randomElement($users) . ' ' .
    //     'status : ' . $faker->randomElements(['a','b','c','d']) . ' ' .
    //     'rule_template_id : ' . $faker->randomElement($ruleTemplate) . ' ' .
    //     'main_rule_id : ' . $faker->randomNumber(1) . ' ' .
    //     'main_rule_condition_1_min : ' . $faker->randomNumber(2) . ' ' .
    //     'main_rule_condition_1_max : ' . $faker->randomNumber(2) . ' ' .
    //     'main_rule_condition_2_min : ' . $faker->randomNumber(2) . ' ' .
    //     'main_rule_condition_2_max : ' . $faker->randomNumber(2) . ' ' .
    //     'main_rule_condition_3_min : ' . $faker->randomNumber(2) . ' ' .
    //     'main_rule_condition_3_max : ' . $faker->randomNumber(2) . ' ' .
    //     'total_weight_kpi : ' . $faker->randomFloat(2, 0, 99999) . ' ' .
    //     'total_weight_key_task : ' . $faker->randomFloat(2, 0, 99999) . ' ' .
    //     'total_weight_key_omg : ' . $faker->randomFloat(2, 0, 99999));
    return [
        'user_id' => 51,
        'period_id' => $faker->randomElement($periods),
        'head_id' => $faker->randomElement($users),
        'status' => 'New',
        'rule_template_id' => $faker->randomElement($ruleTemplate),
        'main_rule_id' => $faker->randomNumber(1),
        'main_rule_condition_1_min' => $faker->randomNumber(2),
        'main_rule_condition_1_max' => $faker->randomNumber(2),
        'main_rule_condition_2_min' => $faker->randomNumber(2),
        'main_rule_condition_2_max' => $faker->randomNumber(2),
        'main_rule_condition_3_min' => $faker->randomNumber(2),
        'main_rule_condition_3_max' => $faker->randomNumber(2),
        'total_weight_kpi' => $faker->randomFloat(5, 0, 5),
        'total_weight_key_task' => $faker->randomFloat(5, 0, 5),
        'total_weight_key_omg' => $faker->randomFloat(5, 0, 5)
    ];
});
