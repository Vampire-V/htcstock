<?php

namespace App\Exports\KPI\Sheets;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersActiveSheet implements FromArray, WithTitle, WithEvents,ShouldAutoSize
{
    private $employee;

    public function __construct(array $employee)
    {
        $this->employee = $employee;
    }

    public function array(): array
    {
        return [
            ['รหัสพนักงาน','ชื่อไทย','ชื่ออังกฤษ','อีเมลล์','ฝ่าย','แผนก','ตำแหน่ง','EMC Group'],
            ...$this->employee
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return 'User set actual';
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
