<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCashFlow extends Model
{
    static public function getTypes()
    {
        return [
            'entry'         => 'Ingreso',
            'egress'        => 'Egreso',
            'direct_payment'=> 'Pago directo'
        ];
    }

    static public function getType($key)
    {   
        $types = self::getTypes();
        return $types[$key];
    }

    static public function getPaymentTypes()
    {
        return [
            'cash'      => 'Efectivo',
            'transfer'  => 'Transferencia',
            'check'     => 'Cheque',
            'deposit'   => 'Deposito'
        ];
    }

    static public function getPaymentType($key)
    {   
        $types = self::getPaymentTypes();
        return $types[$key];
    }

    static public function getDetails()
    {
        return [
            'gathering' => 'Acopio',
            'advance'   => 'Avance',
            'payment'   => 'Pago'
        ];
    }

    static public function getDetail($key)
    {   
        $types = self::getDetails();
        return $types[$key];
    }
    
    public function setDate($date)
    {
        $this->timestamps = false;
        $this->date = $date;
        $this->save();
    }
}