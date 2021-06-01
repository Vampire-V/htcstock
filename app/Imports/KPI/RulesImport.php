<?php

namespace App\Imports\KPI;

use App\Models\KPI\KpiRuleType;
use App\Models\KPI\Rule;
use App\Models\KPI\RuleCategory;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithColumnLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Row;

class RulesImport implements ToModel, WithStartRow, WithColumnLimit, WithBatchInserts
{
    use Importable, RemembersRowNumber;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $rows)
    {
        
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 6;
    }

    public function endColumn(): string
    {
        return 'K';
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
