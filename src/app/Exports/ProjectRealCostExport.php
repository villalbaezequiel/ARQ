<?php
namespace App\Exports;

use App\ProjectRealCost;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProjectRealCostExport implements FromCollection, WithDrawings, WithHeadings, WithCustomStartCell
{
    use Exportable;
 
    private $headings = [
        'RUBRO', 
        'Sub RUBRO',
        'CANTIDAD',
        'PRECIO UNITARIO',
        'IMPUESTO',
        'TOTAL',
        'PAGADO',
        'SALDO ITEM',
        'VARIACION $',
        'VARIACION %',
        'FECHA CREACION'
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
        return $this->realcost ?: ProjectRealCost::all();
    }

}
