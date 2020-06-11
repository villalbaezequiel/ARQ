<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubItem extends Model
{
    static public function getName($id)
    {
        $item = SubItem::find($id);
        
        return ($item ? $item->name : 'Sin Sub Rubro'); 
    }
}
