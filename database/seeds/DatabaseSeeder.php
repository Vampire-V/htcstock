<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // KPI
        $this->call([
            // RuleCategoryTableSeeder::class,
            // TargetUnitTableSeeder::class,
            // TemplateSeeder::class,
            // TargetPeriodSeeder::class,
            RuleSeeder::class,
            // EvaluateSeeder::class,
            // EvaluateDetailSeeder::class
        ]);
        // $this->call([
        //     AccessoriesTableSeeder::class,
        //     PermissionTableSeeder::class,
        //     RoleTableSeeder::class,
        //     DepartmentSeeder::class,
        //     UsersTableSeeder::class,
        //     DivisionSeeder::class,
        //     PositionSeeder::class,


        //     LegalActionTableSeeder::class,
        //     LegalAgreementTableSeeder::class,
        //     LegalPaymentTypeTableSeeder::class,
        //     LegalSubtypeContractTableSeeder::class
        // ]);
    }
}
