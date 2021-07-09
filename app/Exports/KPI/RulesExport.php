<?php

namespace App\Exports\KPI;

use App\Models\KPI\Rule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RulesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Rule::with(['category','ruleType','user','department','parent_to'])->get();
    }


    public function map($rule): array
    {

        return [
            $rule->id,
            $rule->name,
            $rule->category->name,
            $rule->description,
            $rule->calculate_type,
            $rule->quarter_cal,
            $rule->ruleType->name,
            $rule->user->name,
            $rule->base_line,
            $rule->max,
            $rule->desc_m,
            $rule->department->name,
            $rule->parent_to->name
        ];
    }

    public function headings() : array {

        return [
           '#',
           'Rule Name',
           'Rule Category',
           'Detinition ',
           'Calculate Type',
           'Quarter Calculate',
           'Rule Type',
           'User Actual',
           'Base Line',
           'Max',
           'Calculation Machianism',
           'DataSources',
           'Rule KPI'
        ] ;

    }
}
