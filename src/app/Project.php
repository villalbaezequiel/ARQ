<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    static public function getDateParseFormat($date, $separartor = '-')
    {
      if($date) {
        $substrDate = substr($date, 0, 10);
        $format = explode('/', $substrDate);
        if($format[2]){
          return $format[2].$separartor.$format[1].$separartor.$format[0];
        } else {
          return "01".$separartor."01".$separartor."1990";
        }
      } else {
        return null;
      }
    }
}
