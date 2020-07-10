<?php
namespace App\Exports;

use App\ProjectBudget;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectBudgetExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $headings = [
        'Rubro', 
        'Sub Rubro',
        'Unidad',
        'Cantidad',
        'Precio Unitario',
        'Sub Total',
        'Impuesto',
        'Total',
        'Fecha Creacion'
    ];
 
    protected $budgets;
 
    public function __construct($budgets = null)
    {
        $this->budgets = $budgets;
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->budgets ?: ProjectBudget::all();
    }

    public function headings() : array
    {
        return $this->headings;
    }
}
