<?php
namespace App\Exports;

use App\ProjectRealCost;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class ProjectRealCostExport implements FromCollection
{
    use Exportable;
 
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
}
