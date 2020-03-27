<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    static public function getAllProviders($status = '1')
    {
      return Provider::select('providers.*', 'users.name', 'users.last_name', 'users.active')
              ->join('users', 'users.id', '=', 'providers.id_user')
              ->where('users.active', $status)
              ->get();
    }

    static public function getCBU($id)
    {
      $crew = Provider::find($id);

      if($crew){
        return $crew->cbu;
      }
    }

    static public function getTipoCuenta()
    {
      $list = array('Caja de ahorro $', 'Cuenta Corriente $');
      return $list;
    }
}
