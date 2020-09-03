<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exports\ProjectBudgetExport;
use App\Item;
use App\SubItem;
use App\Helpers;

class ProjectBudget extends Model
{
    static public function exportBudget($idProject = null)
    {
        try {
            $timestamp      = date('mdhis');
            $projectBudget  = ProjectBudget::select(
                                            "id_item",
                                            "id_subitem",
                                            "unit",
                                            "quantity",
                                            "unit_cost",
                                            "subtotal",
                                            "tax",
                                            "total",
                                            "created_at",
                                        )->where('id_project', $idProject)->get();

            foreach($projectBudget as &$value)
            {
                $value->id_item     = Item::getName($value->id_item);
                $value->id_subitem  = SubItem::getName($value->id_subitem);
            }

            return (new ProjectBudgetExport($projectBudget))->download("presupuesto_{$idProject}_{$timestamp}.xls");

        } catch (\Exception $err) {
            return $err;
        }
    }
}
