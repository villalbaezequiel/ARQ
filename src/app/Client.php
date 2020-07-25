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

    static public function export()
    {
      try {
          $clients  = Client::select(
                                "users.last_name",
                                "users.name",
                                "users.dni",
                                "users.telephone",
                                "users.email",
                                "clients.contact_last_name",
                                "clients.contact_name",
                                "clients.contact_dni",
                                "clients.contact_telephone",
                                "clients.contact_other_telephone",
                                "clients.contact_email",
                                "clients.billing_razon_social",
                                "clients.billing_cuit",
                                "clients.billing_gross_income",
                                "clients.billing_condition_iva",
                                "clients.billing_id_country",
                                "clients.billing_id_province",
                                "clients.billing_location",
                                "clients.billing_address",
                                "clients.billing_address_number",
                                "clients.billing_piso_depto",
                                "clients.billing_cp",
                                "users.is_active"
                              )
                              ->join('users', 'users.id', 'clients.id_user')
                              ->get();
            foreach ($clients as $client)
            {
              $client->billing_condition_iva = ($client->billing_condition_iva == "0" ? "No Inscripto" : "Inscripto");
              $client->billing_id_country = Helpers::getCountry($client->billing_id_country);
              $client->billing_id_province= Helpers::getProvince($client->billing_id_province);
              $client->is_active          = Helpers::getActive($client->is_active);
            }

            return $clients;

      } catch (\Exception $err) {
          return $err;
      }
    }
}
