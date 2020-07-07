<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubItem extends Model
{
    static public function getName($id)
    {
        $item = SubItem::find($id);
        
        return ($item ? $item->name : '-'); 
    }

    public function getNumber()
    {
        return ($this ? "{$this->number_parent}.{$this->number}" : 0); 
    }

    static public function getNumberFromId($id)
    {
        $subItem = SubItem::find($id);

        return ($subItem ? "{$subItem->number_parent}.{$subItem->number}" : 0); 
    }
}
