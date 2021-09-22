<?php

namespace App\Exports\KPI;

use App\Enum\KPIEnum;
use App\Exports\KPI\Sheets\CalculateTypeSheet;
use App\Exports\KPI\Sheets\ColumnTemplateSheet;
use App\Exports\KPI\Sheets\DataSourcesSheet;
use App\Exports\KPI\Sheets\QuarterCalculateSheet;
use App\Exports\KPI\Sheets\RuleCategorySheet;
use App\Exports\KPI\Sheets\RuleKpiParentSheet;
use App\Exports\KPI\Sheets\RuleTypeSheet;
use App\Exports\KPI\Sheets\UsersActiveSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TemplateRulesExport implements WithMultipleSheets
{
    use Exportable;

    protected $year;

    public function __construct(array $employee, array $category,array $ruletype, array $department, array $rules)
    {
        $this->employee = $employee;
        $this->category = $category;
        $this->ruletype = $ruletype;
        $this->department = $department;
        $this->rules = $rules;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new ColumnTemplateSheet();
        $sheets[] = new UsersActiveSheet($this->employee);
        $sheets[] = new RuleCategorySheet($this->category);
        $sheets[] = new RuleTypeSheet($this->ruletype);
        $sheets[] = new CalculateTypeSheet([[KPIEnum::positive],[KPIEnum::negative],[KPIEnum::zero_oriented_kpi]]);
        $sheets[] = new QuarterCalculateSheet([[KPIEnum::average],[KPIEnum::sum],[KPIEnum::last_month]]);
        $sheets[] = new DataSourcesSheet($this->department);
        $sheets[] = new RuleKpiParentSheet($this->rules);
        return $sheets;
    }
}
