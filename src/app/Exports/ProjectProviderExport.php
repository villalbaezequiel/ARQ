<?php

namespace App\Exports;

use App\ProjectCashFlow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectProviderExport implements FromCollection, WithHeadings
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

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->realcost ?: ProjectCashFlow::all()->toArray());
    }
}
