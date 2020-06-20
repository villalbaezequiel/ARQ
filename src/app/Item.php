<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    static public function getName($id)
    {
        $item = Item::find($id);
        
        return ($item ? $item->name : 'Sin Rubro'); 
    }

    static public function getNumberFromId($id)
    {
        $item = Item::find($id);
        
        return ($item ? $item->number : 0); 
    }

    public function getNumber()
    {
        return ($this ? $this->number : 0); 
    }
    
}
