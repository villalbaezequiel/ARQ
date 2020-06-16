<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SubItem;

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
}
