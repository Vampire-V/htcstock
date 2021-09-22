<?php

namespace App\Exports\KPI\Sheets;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class DataSourcesSheet implements FromArray, WithTitle, WithEvents, ShouldAutoSize
{
    private $department;

    public function __construct(array $department)
    {
        $this->department = $department;
    }

    public function array(): array
    {
        return [
            ['ชื่อ'],
            ...$this->department
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return 'Data Sources';
    }

        /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $activeSheet = $event->sheet->getDelegate();

                // set protect sheet
                $protection = $activeSheet->getProtection();
                $protection->setPassword(Auth::user()->username);
                $protection->setSheet(true);
            },
        ];
    }
}
