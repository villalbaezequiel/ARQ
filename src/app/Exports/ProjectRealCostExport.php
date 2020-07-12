<?php
namespace App\Exports;

use App\ProjectRealCost;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectRealCostExport implements FromCollection, WithHeadings
{
    use Exportable;
 
    private $headings = [
        'Rubro', 
        'Sub Rubro',
        'Cantidad',
        'Precio Unitario',
        'Impuesto',
        'Total',
        'Pagado',
        'Saldo Item',
        'Variacion $',
        'Variacion %',
        'Fecha Creacion'
    ];

    protected $realcost;
 
    public function __construct($realcost = null)
    {
        $this->realcost = $realcost;
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->realcost ?: ProjectRealCost::all();
    }

    public function headings() : array
    {
        return $this->headings;
    }
}
