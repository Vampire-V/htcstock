<?php

use App\Models\Permission;
use App\Models\System;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $it = System::where('slug','it')->first();
        $legal = System::where('slug','legal')->first();
        $kpi = System::where('slug','kpi')->first();
        $permissions = [
            // ------------------------------ IT Stock --------------------------------
            [
                'name' => 'Create Buy',
                'slug' => 'create-buy',
                'system_id' => $it->id
            ],
            [
                'name' => 'Edit Buy',
                'slug' => 'edit-buy',
                'system_id' => $it->id
            ],
            [
                'name' => 'Show Buy',
                'slug' => 'show-buy',
                'system_id' => $it->id
            ],
            [
                'name' => 'Delete Buy',
                'slug' => 'delete-buy',
                'system_id' => $it->id
            ],
            [
                'name' => 'Create Lend',
                'slug' => 'create-lend',
                'system_id' => $it->id
            ],
            [
                'name' => 'Edit Lend',
                'slug' => 'edit-lend',
                'system_id' => $it->id
            ],
            [
                'name' => 'Show Lend',
                'slug' => 'show-lend',
                'system_id' => $it->id
            ],
            [
                'name' => 'Delete Lend',
                'slug' => 'delete-lend',
                'system_id' => $it->id
            ],
            [
                'name' => 'Create Requisition',
                'slug' => 'create-requisition',
                'system_id' => $it->id
            ],
            [
                'name' => 'Edit Requisition',
                'slug' => 'edit-requisition',
                'system_id' => $it->id
            ],
            [
                'name' => 'Show Requisition',
                'slug' => 'show-requisition',
                'system_id' => $it->id
            ],
            [
                'name' => 'Delete Requisition',
                'slug' => 'delete-requisition',
                'system_id' => $it->id
            ],
            // ------------------------------ Legal --------------------------------
            [
                'name' => 'Create Legal Contract',
                'slug' => 'create-legal-contract',
                'system_id' => $legal->id
            ],
            [
                'name' => 'Edit Legal Contract',
                'slug' => 'edit-legal-contract',
                'system_id' => $legal->id
            ],
            [
                'name' => 'Show Legal Contract',
                'slug' => 'show-legal-contract',
                'system_id' => $legal->id
            ],
            [
                'name' => 'Delete Legal Contract',
                'slug' => 'delete-legal-contract',
                'system_id' => $legal->id
            ],
            // ------------------------------ KPI --------------------------------
            [
                'name' => 'Create KPI Template',
                'slug' => 'create-kpi-template',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Edit KPI Template',
                'slug' => 'edit-kpi-template',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Show KPI Template',
                'slug' => 'show-kpi-template',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Delete KPI Template',
                'slug' => 'delete-kpi-template',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Create KPI Periods',
                'slug' => 'create-kpi-periods',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Edit KPI Periods',
                'slug' => 'edit-kpi-periods',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Show KPI Periods',
                'slug' => 'show-kpi-periods',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Delete KPI Periods',
                'slug' => 'delete-kpi-periods',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Create KPI Rules',
                'slug' => 'create-kpi-rules',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Edit KPI Rules',
                'slug' => 'edit-kpi-rules',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Show KPI Rules',
                'slug' => 'show-kpi-rules',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Delete KPI Rules',
                'slug' => 'delete-kpi-rules',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Create KPI Rules Template',
                'slug' => 'create-kpi-rules-template',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Edit KPI Rules Template',
                'slug' => 'edit-kpi-rules-template',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Show KPI Rules Template',
                'slug' => 'show-kpi-rules-template',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Delete KPI Rules Template',
                'slug' => 'delete-kpi-rules-template',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Create KPI Categories',
                'slug' => 'create-kpi-categories',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Edit KPI Categories',
                'slug' => 'edit-kpi-categories',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Show KPI Categories',
                'slug' => 'show-kpi-categories',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Delete KPI Categories',
                'slug' => 'delete-kpi-categories',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Create KPI Evaluates',
                'slug' => 'create-kpi-evaluates',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Edit KPI Evaluates',
                'slug' => 'edit-kpi-evaluates',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Show KPI Evaluates',
                'slug' => 'show-kpi-evaluates',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Delete KPI Evaluates',
                'slug' => 'delete-kpi-evaluates',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Create KPI Evaluates Detail',
                'slug' => 'create-kpi-evaluates-detail',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Edit KPI Evaluates Detail',
                'slug' => 'edit-kpi-evaluates-detail',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Show KPI Evaluates Detail',
                'slug' => 'show-kpi-evaluates-detail',
                'system_id' => $kpi->id
            ],
            [
                'name' => 'Delete KPI Evaluates Detail',
                'slug' => 'delete-kpi-evaluates-detail',
                'system_id' => $kpi->id
            ],
        ];

        foreach ($permissions as $key => $value) {
            Permission::firstOrCreate($value);
        }
    }
}
