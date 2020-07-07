<?php
namespace App\Exports;

use App\ProjectBudget;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProjectBudgetExport implements FromCollection
{
    use Exportable;
 
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
}
