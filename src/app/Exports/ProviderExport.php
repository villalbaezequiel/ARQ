<?php

namespace App\Exports;

use App\Provider;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProviderExport implements FromCollection, WithHeadings
{
    use Exportable;
 
    private $headings = [
        'NOMBRE O RAZON SOCIAL', 
        'CUIT',
        'RUBROS',
        'CONTACTO_NOMBRE',
        'CONTACTO_CARGO',
        'CONTACTO_TELEFONO',
        'CONTACTO_EMAIL',
        'BANCO',
        'BANCO_TIPO_CUENTA',
        'BANCO_NUM_CUENTA',
        'BANCO_CBU',
        'BANCO_ALIAS',
        'SEGURO1_NUM_POLIZA',
        'SEGURO1_VENCIMIENTO',
        'SEGURO2_NUM_POLIZA',
        'SEGURO2_VENCIMIENTO',
        'SEGURO3_NUM_POLIZA',
        'SEGURO3_VENCIMIENTO',
        'PROVEEDOR_ACTIVO'
    ];
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Provider::export();
    }

    public function headings() : array
    {
        return $this->headings;
    }
}
