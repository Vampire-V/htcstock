<?php

use App\Models\KPI\Rule;
use App\Models\KPI\RuleCategory;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Rule::class, 10)->create();
        $kpi = RuleCategory::where('name', 'kpi')->first()->id;
        $omg = RuleCategory::where('name', 'omg')->first()->id;
        $task = RuleCategory::where('name', 'key-task')->first()->id;
        $rules = [
            ['name' => 'Revenue', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Revenue-SAC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Revenue-WAC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Revenue-RF-Haier', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Revenue-RF-AQUA', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Revenue-RF', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Revenue-AC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'RM Margin', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'RM Margin-SAC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'RM Margin-WAC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'RM Margin-RF-Haier', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'RM Margin-RF-AQUA', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'RM Margin-RF', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'RM Margin-AC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'FG Delivery on time-AC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Delivery on time-RF-Haier', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Delivery on time-RF', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Delivery on time-AC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'RM Delivery on time-AC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'WH (FG /RM )stock DIFF-AC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Efficiency of per Capita-SAC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Efficiency of per Capita-WAC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Efficiency of per Capita-RF-Haier', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Efficiency of per Capita-RF-AQUA', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'DIO', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'DIO-SAC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'DIO-RF-Haier', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'DIO-RF-AQUA', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Expenses (MFG+R&D)-SAC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Expenses (MFG+R&D)-WAC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Expenses (MFG+R&D)-RF-Haier', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Expenses (MFG+R&D)-RF-AQUA', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Production achieve plan-WAC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'RM Production stop line-WAC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'RM Production stop line-Rf-Haier', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Production loss-WAC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Production loss-RF-Haier', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Production loss-RF-AQUA', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'M/C Breakdown-AC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'M/C Breakdown-RF', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'New model launch on time-RF', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'FG Aging (FG 60+ inventory)ratio ≤3% (FG 180=0 )-RF', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'FG Aging (FG 60+ inventory)ratio ≤3% (FG 180=0 )-AC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Overdue', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Overdue-RF', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Overdue-AC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Sales Expense-RF', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Sales Expense-AC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'BOM Achievement', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'DIO RM', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Reject rate', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Department Expense Control-SCM', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Department Expense Control-FA', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Department Expense Control-UT', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Import Cost - RF/SAC/WAC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Export Cost - RF/SAC/WAC', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Expense Control', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'CCC', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Aging Stock (30% Reduced from last year)', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'HR cost Efficiency', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Operating expenses', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Recruitment on time', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Staff efficiency', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'EMC timely Evaluation', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Risk Management', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Ontime Services and Quality Compliance', 'category_id' => $kpi, 'calculate_type' => 'Percent'],
            ['name' => 'Operations（Zero Break Down，Zero Problem)', 'category_id' => $kpi, 'calculate_type' => 'Amount'],
            ['name' => 'Energy Expense control', 'category_id' => $kpi, 'calculate_type' => 'Percent'],

            ['name' => 'B Class and Above Employees', 'category_id' => $omg],
            ['name' => 'SOP & Policies implementation', 'category_id' => $omg],
            ['name' => 'Report and Meeting Management', 'category_id' => $omg],
            ['name' => 'New Product', 'category_id' => $task],
            ['name' => 'Production loss improvement', 'category_id' => $task],
            ['name' => 'Efficiency improvement', 'category_id' => $task],
            ['name' => 'Inventory management', 'category_id' => $task],
            ['name' => 'FG aging clearing', 'category_id' => $task],
            ['name' => 'Quality improvement', 'category_id' => $task],
            ['name' => 'Warehouse management', 'category_id' => $task],
            ['name' => 'Production improvement', 'category_id' => $task],
            ['name' => 'Manpower management', 'category_id' => $task],
            ['name' => 'Purchasing management', 'category_id' => $task],
            ['name' => 'Equipment management', 'category_id' => $task],
            ['name' => 'Oder shipment', 'category_id' => $task],
            ['name' => 'Marketing Oder', 'category_id' => $task],
            ['name' => 'Accounts receivable', 'category_id' => $task],
            ['name' => 'Product develop', 'category_id' => $task],
            ['name' => 'Vender management', 'category_id' => $task],
            ['name' => 'Cost saving', 'category_id' => $task],
            ['name' => 'Cost management-WAC', 'category_id' => $task],
            ['name' => 'Cost management-T Door/G3', 'category_id' => $task],
            ['name' => 'Logistic management', 'category_id' => $task],
            ['name' => 'Budget Management', 'category_id' => $task],
            ['name' => 'Asset Management', 'category_id' => $task],
            ['name' => 'Cost Management', 'category_id' => $task],
            ['name' => 'Cash Management', 'category_id' => $task],
            ['name' => 'Accounting Management/Audit/TD', 'category_id' => $task],
            ['name' => 'Organization management', 'category_id' => $task],
            ['name' => 'Recruiting management', 'category_id' => $task],
            ['name' => 'Compensation and benefits management', 'category_id' => $task],
            ['name' => 'Manpower,Attendance- Overtime management', 'category_id' => $task],
            ['name' => 'Training management', 'category_id' => $task],
            ['name' => 'EHS management', 'category_id' => $task],
            ['name' => 'GA management', 'category_id' => $task],
            ['name' => '2021 Budget Activity management', 'category_id' => $task],
            ['name' => 'Report and Analysis management', 'category_id' => $task],
            ['name' => 'Meeting management', 'category_id' => $task],
            ['name' => 'Document management', 'category_id' => $task],
            ['name' => 'Legal management', 'category_id' => $task],
            ['name' => 'Project Closure management', 'category_id' => $task],
            ['name' => 'Inspection and Maintenance as per schedule', 'category_id' => $task],
            ['name' => 'Spare and Asset Management', 'category_id' => $task],
            ['name' => 'EHS & 6S improvement', 'category_id' => $task],

        ];

        foreach ($rules as $key => $value) {
            Rule::firstOrCreate($value);
        }
    }
}
