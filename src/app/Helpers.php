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

    static public function setMessageValidationForm($key)
    {
      return "El $key insertado se encuentra en uso en el Sistema. Por favor, ingrese otro!";
    }

    static public function getCivilStatus()
    {
      $list = array('soltero'=>'Soltero', 'casado'=>'Casado', 'concuvino'=>'Concuvino',
                    'divorciado'=>'Divorciado', 'viudo'=>'Viudo');
      return $list;
    }

    static public function getDate($date)
    {
        return substr($date, 0, 10);
    }

    static public function getDateParseFormat($date, $separartor = '-')
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

      if($format[2]){
        return $format[2].$separartor.$format[1].$separartor.$format[0]." ".$hour;
      } else {
        return "1990".$separartor."01".$separartor."01"." ".$hour;
      }
    }

    static public function getFormatSalary($salary)
    {
      $signo_peso = str_replace(array("$"), array(""), $salary);
      $explode_salary = explode(".", "$salary");
      $decimales = (isset($explode_salary[1]) ? $explode_salary[1] : '00');

      if(strlen($explode_salary[0]) > 3){
        return "$".substr($explode_salary[0], 0, -3).".".substr($explode_salary[0], -3).",".$decimales;
      } else {
        return "$".$signo_peso;
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

   static public function getListOs()
   {
     $list = array(
      '336699' => 'Otra OS/Prepaga',
      '109'	=> 'O.S.PERS.JERARQ.IND.GRAFIC.R.A',
      '208'	=> 'IND.GRAFICA PROV.DE CORDOBA',
      '307'	=> 'PORTUARIOS ARG.MAR DEL PLATA',
      '406'	=> 'OSPOCE-PERS.ORG.CONTROL EXTERN',
      '505'	=> 'CAPITANES,PILOTOS Y PATRONES',
      '604'	=> 'AGENTES DE LOTERIAS Y AFINES',
      '703'	=> 'AGUA Y ENERGIA ELEC MENDOZA',
      '802'	=> 'O.S.DE Y.P.F.',
      '901'	=> 'OSSEG - SEGUROS, REASEGUROS Y',
      '1003'	=> 'OBRAS Y SERVICIOS SANITARIOS',
      '1102'	=> 'OSPLAD - OS ACTIVIDAD DOCENTE',
      '1201'	=> 'OSME - OS MTRIO.ECONOMIA',
      '1300'	=> 'FERROVIARIA',
      '1607'	=> 'AGUAS GASEOSAS DE SANTA FE',
      '1706'	=> 'OSJERA - PERS.JERARQ.IND.GRAF',
      '100106'	=> 'IND.ACEITERA Y AFINES',
      '100205'	=> 'ACTORES',
      '100304'	=> 'TECNICOS DE VUELO DE LINEAS AE',
      '100403'	=> 'PERS.SUPERIOR EMP.AEROCOMERCIA',
      '100502'	=> 'PERSONAL AERONAUTICO',
      '100601'	=> 'AERONAVEGACION DE ENTES PRIVAD',
      '100700'	=> 'TECNICO AERONAUTICO',
      '100809'	=> 'AERONAVEGANTES',
      '100908'	=> 'AGENCIAS DE INFORMES',
      '101000'	=> 'AGUAS GASEOSAS Y AFINES',
      '101109'	=> 'ALFAJOR/REPOSTER/PIZZEROS MDP',
      '101208'	=> 'IND.DE ALIMENTACION',
      '101307'	=> 'O.S.PERS.ARTES GRAF.DEL CHACO',
      '101406'	=> 'O.S.PERS.ARTES GRAF.DE STA.FE',
      '101505'	=> 'O.S.ARTISTAS DE VARIEDADES',
      '101604'	=> 'A.C.A. - AUTOMOVIL CLUB ARGENT',
      '101703'	=> 'O.S.PERS.AZUCAR DE CALILEGUA',
      '101802'	=> 'INGENIO LA ESPERANZA',
      '101901'	=> 'INGENIO LEDESMA',
      '102003'	=> 'O.S.PERS.AZUCAR ING.LAS TOSCAS',
      '102102'	=> 'INGENIO RIO GRANDE',
      '102201'	=> 'O.S.PERS.AZUCAR ING.SAN ISIDRO',
      '102300'	=> 'INGENIO SAN MARTIN',
      '102409'	=> 'O.S.PERS.AZUCAR VILLA OCAMPO',
      '102607'	=> 'O.S.PERS.ACTIV.AZUCARERA TUCUM',
      '102706'	=> 'IND.AZUCARERA',
      '102805'	=> 'O.S.BANCARIOS',
      '102904'	=> 'BARRACAS DE LANAS/CUEROS',
      '103006'	=> 'IND.BOTONERA',
      '103105'	=> 'IND.DEL CALZADO',
      '103204'	=> 'COND.CAMIONEROS/TRANSPORTE AUT',
      '103303'	=> 'O.S.PERS.JUNTA NAC.DE CARNES',
      '103402'	=> 'CARGA Y DESCARGA',
      '103600'	=> 'AUXILIAR CASAS PARTICULARES',
      '103709'	=> 'IND.DEL CAUCHO',
      '103808'	=> 'PERSONAL DEL CAUCHO',
      '103907'	=> 'IND.DEL CAUCHO DE SANTA FE',
      '104009'	=> 'CEMENTERIOS',
      '104108'	=> 'CERAMISTAS',
      '104207'	=> 'CERAMICA/SANITARIOS/PORCELANA',
      '104306'	=> 'ACTIVIDAD CERVECERA Y AFINES',
      '104405'	=> 'CINEMATOGRAFICO MAR DEL PLATA',
      '104504'	=> 'IND.CINEMATOGRAFICA',
      '104603'	=> 'OPERADORES CINEMATOGRAFICO',
      '104702'	=> 'COLCHONEROS',
      '104801'	=> 'COLOC.AZULEJ/MOSA/GRANIT/LUSTR',
      '104900'	=> 'O.S.EMPLEADOS DE COMERCIO',
      '105002'	=> 'CONDUCTORES NAVALES',
      '105101'	=> 'O.S.CONSIG.MDO.GRAL.AVELLANEDA',
      '105200'	=> 'INS.S.S.PERS.IND.CONSTRUCCION',
      '105309'	=> 'ADMIN.Y TECN.CONSTRUCCION',
      '105408'	=> 'CONSTRUIR SALUD -OSPECON',
      '105507'	=> 'CORTADORES DE INDUMENTARIA',
      '105606'	=> 'IND.DEL CUERO Y AFINES',
      '105705'	=> 'IND.DEL CHACINADO Y A',
      '105804'	=> 'CHOFERES DE CAMIONES',
      '105903'	=> 'O.S.CHOFERES PARTICULARES',
      '106005'	=> 'OSPEDYC - ENT.DEPORTIVAS Y CIV',
      '106104'	=> 'DESPACHANTES DE ADUANA',
      '106203'	=> 'DISTRIBUIDORES CINEMATOGRAFIC',
      '106302'	=> 'DOCENTES PARTICULARES',
      '106401'	=> 'OSPERYHRA -',
      '106500'	=> 'OSPERYH CAP.FED.Y G.B.A',
      '106609'	=> 'ELECTRICISTAS NAVALES',
      '106708'	=> 'EMPACADORES FRUTA RIO NEGRO Y',
      '106807'	=> 'ENSEÑANZA PRIVADA',
      '106906'	=> 'PERS. ESCRIBANIAS PCIA. B.A.',
      '107008'	=> 'PERSONAL DE ESCRIBANOS',
      '107107'	=> 'ESPECTACULO PUBLICO',
      '107206'	=> 'EST.SER.GARAG.PLAYAS Y LAVADER',
      '107404'	=> 'PERSONAL DE FARMACIA',
      '107503'	=> 'O.S.PERS.FERIA INFANTIL',
      '107602'	=> 'FERMOLAC',
      '107701'	=> 'O.S.FERROVIARIOS',
      '107800'	=> 'IND. DEL FIBROCEMENTO',
      '107909'	=> 'IND.FIDEERA',
      '108001'	=> 'IND. FORESTAL SGO.ESTERO',
      '108100'	=> 'IND. DEL FOSFORO',
      '108209'	=> 'FOTOGRAFOS',
      '108407'	=> 'PER. ACTIVIDAD FRUCTICOLA',
      '108506'	=> 'MANIPULEO/EMPAQUE/EXPED.FRUTA',
      '108605'	=> 'FUTBOLISTAS',
      '108704'	=> 'TECNICOS DE FUTBOL',
      '108803'	=> 'GASTRONOMICO',
      '108902'	=> 'PERSONAL SUPERIOR DE GOOD YEAR',
      '109004'	=> 'GRAFICO',
      '109103'	=> 'O.S.PERS.GRAFICO DE CORRIENTES',
      '109202'	=> 'GUINCHEROS Y MAQUINISTAS',
      '109301'	=> 'CONSIG.MERCAD HACIENDA LINIERS',
      '109400'	=> 'IND. HIELO Y MERCADOS PARTICUL',
      '109509'	=> 'HIPODROMOS B.A.Y SAN ISIDRO',
      '109608'	=> 'JOCKEY CLUB BA/HIPOD.PALERMO Y',
      '109707'	=> 'IMPRENTA, DIARIOS Y AFINES',
      '109905'	=> 'O.S.PERS.INGENIO SAN PABLO',
      '110008'	=> 'JABONEROS',
      '110107'	=> 'JARDINEROS, PARQUIST.,VIV Y FL',
      '110206'	=> 'JOCKEY CLUB DE ROSARIO',
      '110305'	=> 'LADRILLERO',
      '110404'	=> 'IND.LADRILLERA A MAQUINA',
      '110503'	=> 'IND.LECHERA',
      '110602'	=> 'LOCUTORES',
      '110701'	=> 'LUZ Y FUERZA - FED.ARGENTINA',
      '110800'	=> 'EMPRESAS DE ELECTRICIDAD',
      '110909'	=> 'LUZ Y FUERZA CORDOBA',
      '111001'	=> 'IND. MADERERA',
      '111209'	=> 'MAESTRANZA',
      '111308'	=> 'MAQINISTAS DE TEATRO Y TV',
      '111407'	=> 'CAPITANES DE ULTRAMAR',
      '111506'	=> 'CAPITANES BAQUEANOS FLUVIALES',
      '111605'	=> 'MARINA MERCANTE',
      '111704'	=> 'APUNTADORES MARITIMOS',
      '111803'	=> 'PERSONAL MARITIMO',
      '111902'	=> 'MECANICOS Y AFI. TRANS AUTOMOT',
      '112004'	=> 'MERCEDES BENZ ARGENTINA',
      '112103'	=> 'UOM - OS DE LA UNION OBRERA ME',
      '112202'	=> 'METALMECANICA SUPERV DE IND.',
      '112301'	=> 'MICROS/OMNIBUS MENDOZA',
      '112400'	=> 'ACTIVIDAD MINERA',
      '112509'	=> 'MODELOS ARGENTINOS',
      '112608'	=> 'IND. MOLINERA',
      '112707'	=> 'MOSAISTA',
      '112806'	=> 'MUSICOS',
      '112905'	=> 'O.S.MUSICOS DE CUYO',
      '113007'	=> 'O.S.MUSICOS DE MAR DEL PLATA',
      '113205'	=> 'JEFES/OFICIALES NAVALES/RADIO',
      '113304'	=> 'JEFES/OFICIALE/MAQ.NAVALES',
      '113403'	=> 'PERSONAL NAVAL',
      '113601'	=> 'IND.DEL NEUMATICO',
      '113700'	=> 'IND. NAVAL',
      '113809'	=> 'COMISARIOS NAVALES',
      '113908'	=> 'PANADERIAS',
      '114000'	=> 'PANADEROS/PASTEL/FACTU/E.RIOS',
      '114109'	=> 'PAPEL CARTON Y QUIMICOS',
      '114208'	=> 'IND.PASTAS ALIMENTICIAS',
      '114307'	=> 'PAST/CONFIT/PIZZEROS/ALFAJ RA',
      '114505'	=> 'PATRONES CABOTAJE DE RIOS Y PU',
      '114604'	=> 'PELETEROS',
      '114703'	=> 'PELUQUERIAS/ESTETICA Y AFINES',
      '114802'	=> 'O.S.OFIC.PELUQUEROS Y PEINADOR',
      '114901'	=> 'PELUQUEROS/PEINADORES ROSARIO',
      '115003'	=> 'PERFUMISTA',
      '115102'	=> 'PRENSA DE BUENOS AIRES',
      '115201'	=> 'IND.PESCADO DE M.DEL PLATA',
      '115300'	=> 'PETROLEROS',
      '115409'	=> 'PETROLEO Y GAS PRIVADO',
      '115508'	=> 'PETROLEROS DE CORDOBA',
      '115607'	=> 'IND.PETROQUIMICA',
      '115706'	=> 'PILOTOS LINEAS AEREAS COM.',
      '115805'	=> 'FABRICAS DE PINTURA',
      '115904'	=> 'O.S.PROFESORES ACADEM. PITMAN',
      '116006'	=> 'IND.DEL PLASTICO',
      '116105'	=> 'ESTIBADORES PORTUARIOS',
      '116204'	=> 'PORTUARIOS ARGENTINOS',
      '116303'	=> 'O.S.PORTUARIOS DE BAHIA BLANCA',
      '116402'	=> 'O.S.PORT.DE NECOCHEA Y QUEQUEN',
      '116501'	=> 'O.S.PORTUARIOS DE ROSARIO',
      '116600'	=> 'O.S.PORTUARIOS DE SAN LORENZO',
      '116709'	=> 'PORTUARIOS PUERTO SAN MARTIN',
      '116808'	=> 'O.S.PORT.PUERTO SAN NICOLAS',
      '116907'	=> 'O.S.PORT.DE SAN PEDRO',
      '117009'	=> 'O.S.PORT.DE SANTA FE',
      '117108'	=> 'O.S.PORT.DE VILLA COSTITUCION',
      '117207'	=> 'PRENSA DE LA RA',
      '117405'	=> 'PRENSA DE BAHIA BLANCA',
      '117603'	=> 'PRENSA DEL CHACO',
      '117702'	=> 'PRENSA DE MAR DEL PLATA',
      '117801'	=> 'PRENSA DE MENDOZA',
      '118002'	=> 'PRENSA DE CORDOBA',
      '118101'	=> 'O.S.PROF.PERS.PAPEL,CARTON Y Q',
      '118200'	=> 'AG.PROPAG. MEDICA DE LA R.A.',
      '118309'	=> 'AG.PROPAG. MEDICA DE CORDOBA',
      '118408'	=> 'AG.PROPAG.MEDICA DE ENTRE RIOS',
      '118507'	=> 'AG.PROPAG.MEDICA ROSARIO',
      '118606'	=> 'PUBLICIDAD',
      '118705'	=> 'IND.QUIMICAS/PETROQ',
      '118804'	=> 'RECIBIDORES DE GRANOS',
      '118903'	=> 'RECOLECCION Y BARRIDO ROSARIO',
      '119005'	=> 'REFINERIAS DE MAIZ',
      '119104'	=> 'O.S.IND.MATERIALES REFRACTARIO',
      '119203'	=> 'RELOJEROS Y JOYEROS',
      '119302'	=> 'OSPRERA-RURALES Y ESTIBAD RA',
      '119401'	=> 'O.S.PERS.INDUSTRIA SALINERA',
      '119500'	=> 'SANIDAD',
      '119609'	=> 'INSTALACIONES SANITARIAS',
      '119708'	=> 'SEG.COM.IND.INV.PRIV.',
      '119807'	=> 'O.S.PERS. DEL SEGURO',
      '119906'	=> 'SERENOS DE BUQUES',
      '120108'	=> 'O.S.TEC. PROF. EMPL.Y SUP. ST.',
      '120207'	=> 'O.S.PERS.STANDARD ELECTRIC',
      '120306'	=> 'SUBTERRANEOS BA SUP.EMP',
      '120405'	=> 'SUBTERRANEOS BA PERS DIRECCION',
      '120504'	=> 'IND. TABACO',
      '120603'	=> 'EMPLEADOS TABACO',
      '120702'	=> 'PERSONAL DE LAS TELECOMU. R.A.',
      '120801'	=> 'COMUNICACIONES - OSTRAC',
      '120900'	=> 'PERSONAL DE TELEVISION',
      '121002'	=> 'IND.TEXTIL (PERSONAL)',
      '121101'	=> 'EMPLEADOS TEXTILES Y AFINES',
      '121200'	=> 'O.S.TINTOREROS,SOMBREREROS Y L',
      '121309'	=> 'IND.DEL TRACTOR',
      '121408'	=> 'O.S.TRANSP.AUTOMOTOR DE ROSARI',
      '121507'	=> 'IND.TRANSP.AUTOM.CORDOBA',
      '121606'	=> 'CONDUCTORES TRANSP.C.PASAJEROS',
      '121705'	=> 'ACTIVIDAD DEL TURF PERS',
      '121804'	=> 'CONDUCTORES TAXIS CORDOBA',
      '121903'	=> 'VAREADORES',
      '122005'	=> 'IND.DEL VESTIDO Y AFINES',
      '122104'	=> 'ANDAR - VIAJANTES VENDEDORES',
      '122203'	=> 'O.S.VIAJANTES DE COMERCIO',
      '122302'	=> 'ACTIVIDAD VIAL',
      '122401'	=> 'IND.DEL VIDRIO (EMPLEADOS)',
      '122500'	=> 'IND.DEL VIDRIO (PERSONAL)',
      '122609'	=> 'ACTIVIDAD VITIVINICOLA',
      '122708'	=> 'O.S.PERS.VIALIDAD NACIONAL',
      '122807'	=> 'VIGIL.SEG.COM.IND.INV.PRI CBA',
      '122906'	=> 'EST.SERV/GARAG/PLAYA.STA.FE',
      '123008'	=> 'EST.SERV/GARAG/PLAYA.DE LA RA',
      '123107'	=> 'TALLERISTAS A DOMICILIO',
      '123206'	=> 'O.S.BAÑEROS Y AFIN P.GRAL.PUEY',
      '123305'	=> 'SOCIEDADES DE AUTORES Y AFINES',
      '123404'	=> 'PRENSA DE ROSARIO',
      '123503'	=> 'PRENSA DE TUCUMAN',
      '123602'	=> 'PERKINS ARGENTINA S.A.I.C.',
      '123701'	=> 'PEONES TAXIS DE LA CAP.FED',
      '123800'	=> 'O.S.PERS.GUARDAVIDAS Y AFINES',
      '123909'	=> 'VENDEDORES AMBULANTES R.A.',
      '124001'	=> 'BOXEADORES AGREMIADOS R.A.',
      '124209'	=> 'O.S.EMBALADORES,DESCARTAD. Y A',
      '124308'	=> 'O.S.EMPLEADOS Y OBREROS GASTRO',
      '124407'	=> 'O.S.PERS.LIMP.Y MAEST.DE MZA.',
      '124506'	=> 'IND.DEL GAS',
      '124704'	=> 'O.S.TRAB.IND.AVICOLA DE LA R.A',
      '124803'	=> 'O.S.ARBITROS DE LA A.F.A.',
      '124902'	=> 'O.S.F.N.SIND.CONDUCT.DE TAXIS',
      '125004'	=> 'YACIMIENTOS CARBONIFEROS',
      '125103'	=> 'AGUA Y ENERGIA ELEC PROF UNIV',
      '125202'	=> 'O.S.CAP.PIL.Y PATRONES PESCA',
      '125301'	=> 'OBRAS SANITARIAS (FED.TRAB.)',
      '125400'	=> 'O.S.PERS.OBRAS Y SERV.SANIT.',
      '125509'	=> 'UNIVERSIDADES NACIONALES',
      '125608'	=> 'O.S.PERS.SUP.IND.DEL GAS Y AFI',
      '125707'	=> 'UNION PERS.CIVIL DE LA NACION',
      '125806'	=> 'O.S.PERS.JERARQ.AGUA Y ENERGIA',
      '125905'	=> 'OSADRA -ARBITROS DEPORTIVOS RA',
      '126007'	=> 'IND.DE LA CARNE Y DERIV FED GR',
      '126106'	=> 'EDUCACION PRIVADA OSTEP',
      '126205'	=> 'OSECAC - COMER.Y ACTIV.CIVIL',
      '126304'	=> 'BANCARIA ARGENTINA',
      '200103'	=> 'O.S.SECRET.AGRICULTURA Y GANAD',
      '200301'	=> 'D.I.O.S.O.S.S.IN.CO.EX.CO.INTE',
      '200400'	=> 'ENCOTEL SA /COMUNIC DE LA RA',
      '200509'	=> 'O.S.MINISTERIO DE DEFENSA',
      '200608'	=> 'O.S.FLOTA FLUVIAL ESTADO ARG.',
      '200707'	=> 'O.S.GAS DEL ESTADO',
      '200806'	=> 'DIR.GRAL.O.S.MINISTERIO INTERI',
      '200905'	=> 'O.S.MINISTERIO DE JUSTICIA',
      '201007'	=> 'O.S.EMP.LINEAS MARITIMAS ARG.',
      '201106'	=> 'DIR.GRAL.O.S.MIN.RELAC.EXTERIO',
      '201205'	=> 'O.S.MTRIO.OBRAS Y SERV.PUBLICO',
      '201304'	=> 'DIR.O.S.EMP.NAC.DE TELECOMUNIC',
      '201403'	=> 'O.S.UNIVERSIDAD DE BS.AS.',
      '201502'	=> 'DIRECCION NACIONAL DE VIALIDAD',
      '201700'	=> 'O.S.YACIMIENTOS CARBONIFEROS F',
      '201809'	=> 'O.S.YACIMIENTOS PETROLIFEROS F',
      '201908'	=> 'INS.N.O.S.PER.AD.PUB.NAC.ENTES',
      '300100'	=> 'O.S.ANILSUD',
      '300308'	=> 'O.S.BOROQUIMICA S.A.M.I.C.A.F.',
      '300407'	=> 'O.S.EMPRESA PRIV.CELULOSA ARG.',
      '300506'	=> 'CORPORACION CEMENTERA ARGENTIN',
      '300605'	=> 'O.S.PERS.CERAMICA SAN LORENZO',
      '300704'	=> 'CERAS JOHNSON',
      '300803'	=> 'O.S.CALILEGUA S.A.A.I.C.',
      '301004'	=> 'O.S.DESTILERIAS SAN IGNACIO',
      '301103'	=> 'O.S.DUNLOP ARGENTINA LIMITADA',
      '301202'	=> 'O.S.DUPERIAL ORBEA',
      '301301'	=> 'O.S.COMPAÑIA EMBOTELLADORA ARG',
      '301400'	=> 'O.S.ELECTROCLOR S.C.A.',
      '301509'	=> 'FIRESTONE MUTUALIDAD EMPLEADOS',
      '301608'	=> 'O.S.AUTOLATINA ARG.S.A.',
      '301707'	=> 'ALLIET DOMECO ARGENTINA',
      '301806'	=> 'O.S.SOC.MINERA HIERRO PATAG.SI',
      '302106'	=> 'JOHN DEERE ARGENTINA DIR/TEC',
      '302205'	=> 'O.S.LEDESMA S.A.A.I.',
      '302304'	=> 'COMPAÑIA MINERA AGUILAR S.A.',
      '302403'	=> 'O.S.MOLINOS RIO DE LA PLATA',
      '302502'	=> 'O.S.PASA PETROQUIMICA ARG.S.A.',
      '302601'	=> 'PIRELLI LIMITADA COOP ASIT MUT',
      '302700'	=> 'O.S.COMP. QUIMICA S.A.',
      '302809'	=> 'REFINERIAS DE MAIZ SAICF',
      '302908'	=> 'DIR.GRAL.SERV.ASIST.SERV.ELEC.',
      '303000'	=> 'O.S.SULFACID S.A.I.F.C.',
      '303109'	=> 'SUPERCO',
      '303208'	=> 'EMPRESA PRIVADA WITCEL S.A',
      '303307'	=> 'CABOT ARGENTINA',
      '303406'	=> 'PERSONAL DE SHELL-CAPSA',
      '303505'	=> 'O.S.CIA.GRAL.DE COMBUSTIBLES',
      '303604'	=> 'O.S.DE IPAKO S.A',
      '303703'	=> 'PBBPOLISUR DE BAHIA BLANCA',
      '303802'	=> 'O.S.ARBITROS FUTBOL ARGENTINO',
      '303901'	=> 'FORD ARGENTINA S.A.',
      '304003'	=> 'VOLKSWAGEN ARGENTINA S.A.',
      '400107'	=> 'ALIMENT.Y ACTIV.EMPRES',
      '400206'	=> 'IND.AUTOMOTRIZ ARGENTINA/P.DIR',
      '400305'	=> 'IND.DEL CAUCHO COOP.LIM.ASIT.M',
      '400404'	=> 'IND.CERVECERA/MALTERA(PER.DIR)',
      '400503'	=> 'IND.CIGARRILLO/PERS.DIR Y JERA',
      '400602'	=> 'IND.CONSTRUCCION/PER.DIRECTIVO',
      '400701'	=> 'CAMARA IND.CURTIDORA ARGENTINA',
      '400800'	=> 'OSDE',
      '400909'	=> 'ASE ACCION SOCIAL DE EMPRESARI',
      '401001'	=> 'FRUTOS DEL PAIS/PERS.DIREC.EMP',
      '401100'	=> 'PERS.DIREC.ALFREDO FORTABAT',
      '401209'	=> 'IND.METALURGICA/PERS.DIR',
      '401308'	=> 'IND.MADERERA/PERS.DIR',
      '401407'	=> 'O.S.PERS.DIREC.ACT.MINERA',
      '401506'	=> 'ASO.PRESTACIONES SOCIALES-APS',
      '401605'	=> 'IND.PRIV.DEL PETROLEO/PERS.DIR',
      '401704'	=> 'OSDEPYM EMP,PROF Y MONOTRIBUTI',
      '401803'	=> 'PHILIPS ARGENTINA / AMPAR',
      '401902'	=> 'PERFUMERIAS E.W.HOPE/PERS.DIR',
      '402004'	=> 'LUIS PASTEUR/PERS.DIR DE LA SA',
      '402103'	=> 'IND.SIDERURGICA/PERS.DIR',
      '402202'	=> 'MUTUALIDAD IND. TEXTIL ARG',
      '402301'	=> 'ORGANIZACION TECHINT/ASOC.PERS',
      '402400'	=> 'IND.VITIVINICOLA Y AF/PERS.DIR',
      '500104'	=> 'O.S.SERVICIOS SOCIAL.BANCARIOS',
      '500203'	=> 'O.S.PERS.CIVIL DE LA NACION',
      '500302'	=> 'INS.S.SOC.PERS.IND.CARNES Y AF',
      '500401'	=> 'O.S.EMPL.COMERCIO Y ACTIVIDADE',
      '500500'	=> 'O.S.PERS.ACTIVIDAD DOCENTE',
      '500609'	=> 'INST.S.S.PERS.FERROVIARIO',
      '500708'	=> 'INS.O.S.PERS.MTRIO DE ECONOMIA',
      '500807'	=> 'INSSJP.INS.NAC.SERV.SOC.JUBILA',
      '500906'	=> 'O.S.PERS.O.SANITARIAS NACION',
      '501107'	=> 'INS.S.S.ACTIV.RURALES Y AFINES',
      '501206'	=> 'INS.S.S.PER.SEG.REAS.CAP.AHORR',
      '501404'	=> 'INST.SERV.SOCIAL PARA /T.FUEGO',
      '501503'	=> 'INS.S.S.PERS.IND.VIDRIO Y AFIN',
      '600200'	=> 'ADOS BARILOCHE',
      '600309'	=> 'ADOS BELLA VISTA',
      '600408'	=> 'ADOS COMODORO RIVADAVIA',
      '602305'	=> 'ADOS ROSARIO',
      '602701'	=> 'ADOS SAN JUAN',
      '603100'	=> 'ADOS DE TRELEW',
      '700108'	=> 'PERS. MUNICIPAL DE AVELLANEDA',
      '700801'	=> 'PERS. MUNICIPAL DE LA MATANZA',
      '701002'	=> 'PERS.MUNICIPAL DE 3 DE FEBRERO',
      '701101'	=> 'O.S.PERS.MUNIC.STGO.ESTERO',
      '701200'	=> 'O.S.TRAB.MUNICIP.GRAL.PUEYRRED',
      '800105'	=> 'ATANOR S.A.',
      '800204'	=> 'O.S.FORJA ARGENTINA S.A.I.C.',
      '800303'	=> 'PAPEL MISIONERO S.A.I.F.C.',
      '800402'	=> 'O.S.PETROQUIMICA GRAL.MOSCONI',
      '800501'	=> 'ACEROS PARANA S.A.',
      '900102'	=> 'FIAT, PEUGEOT, CITROEN ARG',
      '900201'	=> 'O.S.OLIVETTI S.C.A.',
      '1904'	=> 'FEDECAMARAS - OS DE LA FED.CAM',
      '126601'	=> 'IND.QUIM/PETROQ.ZARATE CAMPANA',
      '402608'	=> 'ASOC SERV SOC EMPRESARIALES',
      '2204'	=> 'EMPLEADOS DE GOOD YEAR S.R.L',
      '126809'	=> 'CONDUCTORES REMISES Y AUTOS',
      '1508'	=> 'AMAS DE CASA DE LA RA',
      '402509'	=> 'YPF',
      '126502'	=> 'OB.Y EMPL.MUNICIPALES ARG',
      '126700'	=> 'ACT.AZUCARERA TUCUMANA',
      '126908'	=> 'MEDICOS DE LA CIUDAD DE BS.AS.',
      '1805'	=> 'LEGISLADORES DE LA RA',
      '2303'	=> 'LIMPIEZA Y MAESTRANZA DE MZA.',
      '2105'	=> 'PROFESIONALES DEL TURF - RA',
      '126403'	=> 'O.S. MEDICA AVELLANEDA',
      '2402'	=> 'TRANSPORTE AUT. PASAJEROS CBA.',
      '127000'	=> 'ESTACIONES DE SERVICIO (OSTES)',
      '2501'	=> 'MINISTROS SECR Y SUBSECRETARIO',
      '402707'	=> 'OSDO',
      '2600'	=> 'CARNE Y AFINES TRABAJADORES',
      '127208'	=> 'OSMMEDT - TELECOM. PERS JERARQ',
      '2709'	=> 'OS DE LOS TRAB.ASOC.MUT.MERCAN',
      '127307'	=> 'OS DE TRAB.VIALES Y AF. DE RA',
      '402806'	=> 'OS DE DIR. DE LA ACT.AEROCOMER',
      '2808'	=> 'OS REMISES PCIA.BA',
      '127109'	=> 'OS DEL PERSONAL DE TELECOMUNIC',
      '2907'	=> 'OS ASOC.MUT.METAL.VILLA CONSTI',
      '402905'	=> 'OS DE DIRECCION WITCEL',
      '127505'	=> 'ASOC.DE EMP.DE FARMACIA-OSADEF',
      '127406'	=> 'TINTOREROS, SOMBREREROS Y LAVA',
      '3009'	=> 'OS SANCOR',
      '127703'	=> 'OS PERS.EST.SERVICIO CHACO',
      '127604'	=> 'OBRAS Y SERVICIOS SANITARIOS',
      '3207'	=> 'TRAB.SOCIOS PERS.JER.BANCOS OF',
      '3306'	=> 'TRABAJADORES VEND.DIARIOS Y RE',
      '127802'	=> 'LUZ Y FUERZA DE LA PATAGONIA',
      '3108'	=> 'OSPANA - INMIGRANTES ESPANOLES',
      '3405'	=> 'OS ASOC.MUT.OB.CATOL.P.GROTE',
      '127901'	=> 'OS PETROLEROS PRIVADOS',
      '3504'	=> 'OS CONDUCTORES DE TAXIS DE CIU'
     );

     return $list;
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

   static function typesSendNotifications()
   {
    $list = [
      'owner_secure' => 'Responsable de Seguros',
      'center_cost' => 'Centro de Costos',
      'producer_orders' => 'Sistema de Pedidos/Productores'
    ];

    return $list;
   }

   static function setTransformDateTimestamp($date, $separartor = '/', $hour = '00:00:00')
   {
      $setDate = explode($separartor, $date);
      $dateTransform = $setDate[2].'-'.$setDate[1].'-'.$setDate[0].' '.$hour;

      return $dateTransform;
   }

   static function isWeekend($day)
   {
      return ($day == 0 || $day == 6);
   }
   
}
