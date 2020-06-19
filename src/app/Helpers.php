<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Helpers extends Model
{

    static public function print_x($array)
    {
      echo "<pre>";
      print_r($array);
      echo "</pre>";
    }

    static public function getActive($active)
    {
      if($active == 0 || $active == '0'){
        return "No";
      } elseif($active == 1 || $active == '1') {
        return "Si";
      }
    }

    static public function setMessageValidationForm($key)
    {
      return "El $key insertado se encuentra en uso en el Sistema. Por favor, ingrese otro!";
    }

    static public function getDate($date)
    {
        return substr($date, 0, 10);
    }

    static public function getDateParseFormat($date, $separartor = '/')
    {
      $substrDate = substr($date, 0, 10);
      $format = explode('-', $substrDate);
      if($format[2]){
        return $format[2].$separartor.$format[1].$separartor.$format[0];
      } else {
        return "01".$separartor."01".$separartor."1990";
      }
    }

    static public function setDateFormatTimestamp($date, $explodeString = '/', $hour = '00:00:00')
    {
      $substrDate = substr($date, 0, 10);
      $separartor = '-';
      $format = explode($explodeString, $substrDate);

      if(isset($format[2]))
      {
        return $format[2].$separartor.$format[1].$separartor.$format[0]." ".$hour;
      } else {
        return null;
      }
    }

    static public function getListsBank()
    {
      // https://www.bcra.gob.ar/SistemasFinancierosYdePagos/Sistema_financiero_nomina_de_entidades.asp?bco=AAA00&tipo=1
      $list = array(
        '00007' => 'BANCO DE GALICIA Y BUENOS AIRES S.A.U.',
        '00011' => 'BANCO DE LA NACION ARGENTINA',
        '00014' => 'BANCO DE LA PROVINCIA DE BUENOS AIRES',
        '00015' => 'INDUSTRIAL AND COMMERCIAL BANK OF CHINA',
        '00016' => 'CITIBANK N.A.',
        '00017' => 'BBVA BANCO FRANCES S.A.',
        '00018' => 'MUFG BANK, LTD',
        '00020' => 'BANCO DE LA PROVINCIA DE CORDOBA S.A.',
        '00027' => 'BANCO SUPERVIELLE S.A.',
        '00029' => 'BANCO DE LA CIUDAD DE BUENOS AIRES',
        '00034' => 'BANCO PATAGONIA S.A.',
        '00044' => 'BANCO HIPOTECARIO S.A.',
        '00045' => 'BANCO DE SAN JUAN S.A.',
        '00060' => 'BANCO DEL TUCUMAN S.A.',
        '00065' => 'BANCO MUNICIPAL DE ROSARIO',
        '00072' => 'BANCO SANTANDER RIO S.A.',
        '00083' => 'BANCO DEL CHUBUT S.A.',
        '00086' => 'BANCO DE SANTA CRUZ S.A.',
        '00093' => 'BANCO DE LA PAMPA SOCIEDAD DE ECONOMÍA M',
        '00094' => 'BANCO DE CORRIENTES S.A.',
        '00097' => 'BANCO PROVINCIA DEL NEUQUÉN SOCIEDAD ANÓ',
        '00143' => 'BRUBANK S.A.U.',
        '00147' => 'BANCO INTERFINANZAS S.A.',
        '00150' => 'HSBC BANK ARGENTINA S.A.',
        '00165' => 'JPMORGAN CHASE BANK, NATIONAL ASSOCIATIO',
        '00191' => 'BANCO CREDICOOP COOPERATIVO LIMITADO',
        '00198' => 'BANCO DE VALORES S.A.',
        '00247' => 'BANCO ROELA S.A.',
        '00254' => 'BANCO MARIVA S.A.',
        '00259' => 'BANCO ITAU ARGENTINA S.A.',
        '00262' => 'BANK OF AMERICA, NATIONAL ASSOCIATION',
        '00266' => 'BNP PARIBAS',
        '00268' => 'BANCO PROVINCIA DE TIERRA DEL FUEGO',
        '00269' => 'BANCO DE LA REPUBLICA ORIENTAL DEL URUGU',
        '00277' => 'BANCO SAENZ S.A.',
        '00281' => 'BANCO MERIDIAN S.A.',
        '00285' => 'BANCO MACRO S.A.',
        '00299' => 'BANCO COMAFI SOCIEDAD ANONIMA',
        '00300' => 'BANCO DE INVERSION Y COMERCIO EXTERIOR S',
        '00301' => 'BANCO PIANO S.A.',
        '00305' => 'BANCO JULIO SOCIEDAD ANONIMA',
        '00309' => 'BANCO RIOJA SOCIEDAD ANONIMA UNIPERSONAL',
        '00310' => 'BANCO DEL SOL S.A.',
        '00311' => 'NUEVO BANCO DEL CHACO S. A.',
        '00312' => 'BANCO VOII S.A.',
        '00315' => 'BANCO DE FORMOSA S.A.',
        '00319' => 'BANCO CMF S.A.',
        '00321' => 'BANCO DE SANTIAGO DEL ESTERO S.A.',
        '00322' => 'BANCO INDUSTRIAL S.A.',
        '00330' => 'NUEVO BANCO DE SANTA FE SOCIEDAD ANONIMA',
        '00331' => 'BANCO CETELEM ARGENTINA S.A.',
        '00332' => 'BANCO DE SERVICIOS FINANCIEROS S.A.',
        '00336' => 'BANCO BRADESCO ARGENTINA S.A.U.',
        '00338' => 'BANCO DE SERVICIOS Y TRANSACCIONES S.A.',
        '00339' => 'RCI BANQUE S.A.',
        '00340' => 'BACS BANCO DE CREDITO Y SECURITIZACION S',
        '00341' => 'BANCO MASVENTAS S.A.',
        '00384' => 'WILOBANK S.A.',
        '00386' => 'NUEVO BANCO DE ENTRE RÍOS S.A.',
        '00389' => 'BANCO COLUMBIA S.A.',
        '00426' => 'BANCO BICA S.A.',
        '00431' => 'BANCO COINAG S.A.',
        '00432' => 'BANCO DE COMERCIO S.A.',
        '44059' => 'FORD CREDIT COMPAÑIA FINANCIERA S.A.',
        '44077' => 'COMPAÑIA FINANCIERA ARGENTINA S.A.',
        '44088' => 'VOLKSWAGEN FINANCIAL SERVICES COMPAÑIA F',
        '44090' => 'CORDIAL COMPAÑÍA FINANCIERA S.A.',
        '44092' => 'FCA COMPAÑIA FINANCIERA S.A.',
        '44093' => 'GPAT COMPAÑIA FINANCIERA S.A.U.',
        '44094' => 'MERCEDES-BENZ COMPAÑÍA FINANCIERA ARGENT',
        '44095' => 'ROMBO COMPAÑÍA FINANCIERA S.A.',
        '44096' => 'JOHN DEERE CREDIT COMPAÑÍA FINANCIERA S.',
        '44098' => 'PSA FINANCE ARGENTINA COMPAÑÍA FINANCIER',
        '44099' => 'TOYOTA COMPAÑÍA FINANCIERA DE ARGENTINA',
        '44100' => 'FINANDINO COMPAÑIA FINANCIERA S.A.',
        '45056' => 'MONTEMAR COMPAÑIA FINANCIERA S.A.',
        '45072' => 'TRANSATLANTICA COMPAÑIA FINANCIERA S.A.',
        '65203' => 'CAJA DE CREDITO "CUENCA" COOPERATIVA LIM'
      );

      return $list;
    }

    static function getBank($bank)
    {
      $listBank = self::getListsBank();
      foreach ($listBank as $key => $value) {
        if($bank == $key) {
          return $value;
        }
      }
    }

    static function getCapturError($e)
    {
      if($e){
        if(\Config::get('app.debug')):
          $message = 'ERROR('.$e->getLine().'): '.$e->getMessage();
        else:
          $message = 'Se ha producido un error. Por favor intente nuevamente.';
        endif;

        $respuesta = array(
          'estado' => false,
          'mensaje' => $message
        );
        return \Response::json($respuesta);
      }
    }

   static function getDay($day)
   {
      $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");

      return $dias[$day];
   }

   static function setLimitString($strings, $limit = 25)
   {
    if (strlen($strings) > $limit) {
      $str = substr( str_replace(' ', '_', $strings), 0, $limit) . '...';
      return $str;
    } else {
      return $strings;
    }
   }


   static function setTransformDateTimestamp($date, $separartor = '/', $hour = '00:00:00')
   {
      $setDate = explode($separartor, $date);
      $dateTransform = $setDate[2].'-'.$setDate[1].'-'.$setDate[0].' '.$hour;

      return $dateTransform;
   }

   static function getCountries()
   {
      $list = [
        1 => 'Argentina'
      ];
      
      return $list;
   }

   static function getProvinces($id_country = 1)
   {
      $list = [
          1   => 'Buenos Aires',
          2   => 'Catamarca',
          3   => 'Chaco',
          4   => 'Chubut',
          5   => 'Córdoba',
          6   => 'Corrientes',
          7   => 'Entre Ríos',
          8   => 'Formosa',
          9   => 'Jujuy',
          10  => 'La Pampa',
          11  => 'La Rioja',
          12  => 'Mendoza',
          13  => 'Misiones',
          14  => 'Neuquén',
          15  => 'Río Negro',
          16  => 'Salta',
          17  => 'San Juan',
          18  => 'San Luis',
          19  => 'Santa Cruz',
          20  => 'Santa Fe',
          21  => 'Santiago del Estero',
          22  => 'Tierra del Fuego, Antártida e Isla del Atlántico Sur',
          23  => 'Tucumán'
      ];
      
      return $list;  
  }

  static public function getTypesDocumentationPerson()
  {
    $list = array('dni'=>'DNI', 'le'=>'LE', 'CI'=>'CI');
    return $list;
  }
   
  static public function getGendersPerson()
  {
    $list = array('male'=>'Masculino', 'female'=>'Femenino');
    return $list;
  }

  static public function getGenderEs($gender)
  {
    if($gender == "male") {
      return "Masculino";
    } elseif ($gender == "female") {
      return "Femenino";
    }
  }

  static public function getCivilStatus()
  {
    $list = array('soltero'=>'Soltero', 'casado'=>'Casado', 'concuvino'=>'Concuvino',
                  'divorciado'=>'Divorciado', 'viudo'=>'Viudo');
    return $list;
  }

  function setDateFormat($date, $explodeString = '/')
  {
      $substrDate = substr($date, 0, 10);
      $separartor = '-';
      $format = explode($explodeString, $substrDate);

      if($format[2]){
        return $format[2].$separartor.$format[1].$separartor.$format[0];
      } else {
        return "1990".$separartor."01".$separartor."01";
      }
  }
  
}
