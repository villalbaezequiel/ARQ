<?php

namespace App\Exports;

use App\ProjectCashFlow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProjectProviderExport implements FromCollection, WithDrawings, WithHeadings, WithCustomStartCell, WithEvents
{
    use Exportable;
 
    private $headings = [
        'Avance',
        'Fecha',
        'Monto',
        'Impuesto',
        'Forma',
        'Rubro',
        'Sub',
        'Detalle',
        'Aclaracion'
    ];

    protected $realcost;
 
    public function __construct($realcost = null)
    {
        $this->realcost = $realcost;
    }

    public function headings() : array
    {
        return $this->headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'B1:J1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/img/logo_negro.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        
        return $drawing;
    }
    
    public function startCell(): string
    {
        return 'B1';
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->realcost ?: ProjectCashFlow::all();
    }
}
