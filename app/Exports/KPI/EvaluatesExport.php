<?php

namespace App\Exports\KPI;

use App\Enum\KPIEnum;
use App\Models\KPI\Evaluate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EvaluatesExport implements FromQuery ,WithMapping, WithHeadings
{
    use Exportable;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return Evaluate::with(['user', 'targetperiod' => fn ($query) => $query->orderBy('id', 'asc'), 'evaluateDetail.rules.category'])
            ->whereNotIn('status', [KPIEnum::new])
            ->filter($this->request)->orderBy('period_id');
    }

    public function map($evaluate): array
    {
        $evaluate->evaluateDetail->each(function($item) {
            
        });
        $members = [
            'name' => $evaluate->user->name,
        ];
        return $members;
    }

    public function headings(): array
    {
        return [
            'Name'
        ];
    }
}
