<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exports\ProjectRealCostExport;
use App\Item;
use App\SubItem;
use App\Helpers;

class ProjectRealCost extends Model
{
    static public function getNameSubItem($id)
    {
        $projectRealCost = ProjectRealCost::find($id);

        if($projectRealCost) {
            return SubItem::getName($projectRealCost->id_subitem);
        } else {
            return "No existe";
        }   
    }

    static public function exportRealCost($idProject = null)
    {
        try {
            $timestamp      = date('mdhis');
            $projectRealCost= ProjectRealCost::select(
                                                "id_item",
                                                "id_subitem",
                                                "quantity",
                                                "unit_cost",
                                                "tax",
                                                "budget_total",
                                                "paid",
                                                "balance_item",
                                                "budget_variation_pesos",
                                                "budget_variation_tax",
                                                "created_at",
                                            )->where('id_project', $idProject)->get();

            foreach($projectRealCost as &$value)
            {
                $value->id_item     = Item::getName($value->id_item);
                $value->id_subitem  = SubItem::getName($value->id_subitem);
            }

            return (new ProjectRealCostExport($projectRealCost))->download("seguimiento_{$idProject}_{$timestamp}.csv", \Maatwebsite\Excel\Excel::TSV);

        } catch (\Exception $err) {
            return $err;
        }
    }
}
