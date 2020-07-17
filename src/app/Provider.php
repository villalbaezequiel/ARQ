<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;

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

    static public function getTipoCuenta($key = null)
    {
      $list = array('Caja de ahorro $', 'Cuenta Corriente $');
      return (!is_null($key) ? $list[$key] : $list);
    }

    static public function getName($id)
    {
      $provider = Provider::select('users.name')
              ->join('users', 'users.id', '=', 'providers.id_user')
              ->where('providers.id', $id)
              ->first();
              
      return ($provider ? $provider->last_name.' '.$provider->name : '-');

    }

    static public function export()
    {
      try {
          $providers  = Provider::select(
                                "users.name",
                                "users.cuit",
                                "providers.id_items",
                                "providers.contact_name",
                                "providers.contact_cargo",
                                "providers.contact_telephone",
                                "providers.contact_email",
                                "providers.bank",
                                "providers.type_account",
                                "providers.number_account",
                                "providers.cbu",
                                "providers.alias_account",
                                "providers.secure_number_poliza",
                                "providers.secure_expiration",
                                "providers.secure_number_poliza_2",
                                "providers.secure_expiration_2",
                                "providers.secure_number_poliza_3",
                                "providers.secure_expiration_3",
                                "users.is_active"   
                              )
                              ->join('users', 'users.id', 'providers.id_user')
                              ->get();
            foreach ($providers as $provider)
            {
              $provider->bank         = Helpers::getBank($provider->bank);
              $provider->type_account = ($provider->type_account ? self::getTipoCuenta($provider->type_account) : '');
              $provider->is_active    = Helpers::getActive($provider->is_active);
              $provider->secure_expiration  = Helpers::getDateParseFormat($provider->secure_expiration);
              $provider->secure_expiration_2= Helpers::getDateParseFormat($provider->secure_expiration_2);
              $provider->secure_expiration_3= Helpers::getDateParseFormat($provider->secure_expiration_3);
            
              $items = '';
              foreach(json_decode($provider->id_items) as $item)
              {
                $items .= Item::getName($item).', ';
              }
              
              $provider->id_items = substr($items, 0, -2);
            }

            return $providers;

      } catch (\Exception $err) {
          return $err;
      }
    }
}
