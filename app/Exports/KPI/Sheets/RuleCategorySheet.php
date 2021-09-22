<?php

namespace App\Exports\KPI\Sheets;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class RuleCategorySheet implements FromArray, WithTitle,WithEvents, ShouldAutoSize
{
    private $category;

    public function __construct(array $category)
    {
        $this->category = $category;
    }

    public function array(): array
    {
        return [
            ['ชื่อย่อ','ชื่อเต็ม'],
            ...$this->category
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return 'Rule Category';
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
