<?php

namespace App\Exports;

use App\ProjectCashFlow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProjectProviderExport implements FromCollection, WithDrawings, WithHeadings, WithCustomStartCell
{
    use Exportable;
 
    private $headings = [
        'AVANCE',
        'FECHA',
        'MONTO',
        'IMPUESTO',
        'FORMA',
        'RUBRO',
        'SUB',
        'DETALLE',
        'ACLARACION'
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

    public function drawings()
    {   
        try {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('This is my logo');
            $drawing->setPath(public_path('/img/logo_negro.png'));
            $drawing->setHeight(105);
            $drawing->setCoordinates('B1');

            return $drawing;    
        } catch (\Exception $err) {
            //
        }
    }
    
    public function startCell(): string
    {
        return 'A8';
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->realcost ?: ProjectCashFlow::all());
    }
}
