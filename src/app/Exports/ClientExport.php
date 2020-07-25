<?php

namespace App\Exports;

use App\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientExport implements FromCollection, WithHeadings
{
    use Exportable;
 
    private $headings = [
        'NOMBRE', 
        'APELLIDO',
        'DNI',
        'TELEFONO',
        'EMAIL',
        'CONTACTO_NOMBRE',
        'CONTACTO_APELLIDO',
        'CONTACTO_DNI',
        'CONTACTO_TELEFONO',
        'CONTACTO_OTRO_TELEFONO',
        'CONTACTO_EMAIL',
        'FACTURACION_RAZON_SOCIAL_NOMBRE',
        'FACTURACION_CUIT',
        'FACTURACION_INGRESOS_BRUTOS',
        'FACTURACION_CONDICION_IVA',
        'FACTURACION_PAIS',
        'FACTURACION_PROVINCIA',
        'FACTURACION_LOCALIDAD',
        'FACTURACION_DIRECCION',
        'FACTURACION_DIRECCION_NUMERO',
        'FACTURACION_DIRECCION_PISO',
        'FACTURACION_DIRECCION_CP',
        'CLIENTE_ACTIVO'
    ];
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Client::export();
    }

    public function headings() : array
    {
        return $this->headings;
    }
}
