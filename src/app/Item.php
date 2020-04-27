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
}
