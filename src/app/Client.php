<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    static public function getName($id)
    {
      $client = Client::select('users.last_name', 'users.name')->join('users', 'clients.id_user', 'users.id')->where('clients.id', $id)->first();
      return ($client ? $client->last_name." ".$client->name : "-");
    }

    static public function getAllClients($status = '1')
    {
      return Client::where('active', $status)
              ->get();
    }

    static public function getRazonSocial($id)
    {
      $client = Client::find($id);
      if($client){
        return $client->razon_social;
      }
    }
}
