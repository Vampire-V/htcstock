<?php

namespace App\Exports\KPI\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ColumnTemplateSheet implements FromArray, WithTitle, WithEvents, ShouldAutoSize
{

    public function __construct()
    {
    }

    public function array(): array
    {
        return [
            ['name', 'category_id', 'kpi_rule_types_id', 'user_actual', 'calculate_type', 'quarter_cal', 'department_id', 'base_line', 'max', 'parent', 'description', 'desc_m'],
            ['Rule Name', 'Rule Category', 'Rule Type', 'User set actual', 'Calculate Type', 'Quarter Calculate', 'Data Sources', 'Base Line', 'Max', 'Rule KPI', 'Detinition', 'Calculation Machianism'],
            ['required', 'required', 'required', 'required', 'required', 'required', 'required', 'required', 'required', \null, \null, \null],
            ['ตัวอย่าง ชื่อ', 'kpi', 'Financial', '70037959', 'Positive', 'Sum', 'Operation', '70', '100', 'Revenue', 'sdfsdgfdsfdsf', 'asdsdsdasdghgdf']
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return 'Template';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $activeSheet = $event->sheet->getDelegate();
                // set background color
                $activeSheet->getStyle('A3:I3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FA8072');
                $activeSheet->getStyle('J3:L3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ADFF2F');
                $activeSheet->getStyle('A4:L4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDEAD');
                
                $activeSheet->getStyle('A2:L3')->applyFromArray(
                       [
                        //    'font' => [
                        //        'name' => 'Arial',
                        //        'bold' => true,
                        //        'italic' => false,
                        //        'underline' => Font::UNDERLINE_DOUBLE,
                        //        'strikethrough' => false,
                        //        'color' => [
                        //            'rgb' => '808080'
                        //        ]
                        //    ],
                           'borders' => [
                               'allBorders' => [
                                   'borderStyle' => Border::BORDER_THIN,
                                   'color' => [
                                       'rgb' => '000000'
                                   ]
                               ],
                           ],
                           'alignment' => [
                               'horizontal' => Alignment::HORIZONTAL_CENTER,
                               'vertical' => Alignment::VERTICAL_CENTER,
                               'wrapText' => true,
                           ],
                           'quotePrefix'    => true
                       ]
                    );
                // hide row
                $activeSheet->getRowDimension('1')->setVisible(false);

            },
        ];
    }
}
