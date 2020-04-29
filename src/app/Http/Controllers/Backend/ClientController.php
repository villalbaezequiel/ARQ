<?php


namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Helpers;
use App\User;
use App\Client;
use App\UserFile;

class ClientController extends Controller
{
    // protected $_permission;
    // protected $permissionApproved = ['1', '4'];

    // /**
    //  * Instantiate a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //   $this->middleware(function ($request, $next) {
    //     $cancelPermission = true;
    //     $this->_permission = json_decode(Auth::user()->permissions);

    //     if($this->_permission){
    //       foreach($this->permissionApproved as $approved){
    //         foreach($this->_permission as $userPermission){
    //           if($approved == $userPermission){
    //             $cancelPermission = false;
    //             return $next($request);
    //           }
    //         }
    //       }
    //     }

    //   });
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('backend.client.index', [
        'clients' => Client::orderBy('id', 'DESC')->get()
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('backend.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $message;
      try {
        // Validations, check: email, dni
        $email = User::where('email', $request->get('email'))->count();
        $dni   = User::where('dni', $request->get('dni'))->count();
                
        if($email == 0 && $dni == 0):

          // new User
          $user               = new User;
          $user->name         = $request->get('name');
          $user->last_name    = $request->get('last_name');
          $user->email        = $request->get('email');
          $user->dni          = $request->get('dni');
          $user->telephone    = $request->get('telephone');
          $user->other_telephone = $request->get('other_telephone');
          $user->is_active    = $request->get('is_active');

          $user->password     = Hash::make('arq_'.$request->get('dni'));
          $user->is_client    = 1;
          $user->save();

          // new Client
          $client             = new Client;
          $client->id_user    = $user->id;
          $client->contact_last_name  = $request->get('contact_last_name');
          $client->contact_name       = $request->get('contact_name');
          $client->contact_dni        = $request->get('contact_dni');
          $client->contact_telephone  = $request->get('contact_telephone');
          $client->contact_other_telephone = $request->get('contact_other_telephone');
          $client->contact_email      = $request->get('contact_email');

          // data billing
          $client->billing_razon_social = $request->get('billing_razon_social');
          $client->billing_cuit         = $request->get('billing_cuit');
          $client->billing_gross_income = $request->get('billing_gross_income');
          $client->billing_condition_iva= $request->get('billing_condition_iva');
          $client->billing_id_country   = $request->get('billing_id_country');
          $client->billing_id_province  = $request->get('billing_id_province');
          $client->billing_location     = $request->get('billing_location');
          $client->billing_address      = $request->get('billing_address');
          $client->billing_address_number = $request->get('billing_address_number');
          $client->billing_piso_depto   = $request->get('billing_piso_depto');
          $client->billing_cp           = $request->get('billing_cp');

          $client->billing_razon_social_2 = $request->get('billing_razon_social_2');
          $client->billing_cuit_2         = $request->get('billing_cuit_2');
          $client->billing_gross_income_2 = $request->get('billing_gross_income_2');
          $client->billing_condition_iva_2= $request->get('billing_condition_iva_2');
          $client->billing_id_country_2   = $request->get('billing_id_country_2');
          $client->billing_id_province_2  = $request->get('billing_id_province_2');
          $client->billing_location_2     = $request->get('billing_location_2');
          $client->billing_address_2      = $request->get('billing_address_2');
          $client->billing_address_number_2 = $request->get('billing_address_number_2');
          $client->billing_piso_depto_2   = $request->get('billing_piso_depto_2');
          $client->billing_cp_2           = $request->get('billing_cp_2');

          $client->save();

          User::uploadFiles($request, 'files', "$user->id");

          return redirect('/clients')->with('message', 'Cliente creado con exito!');

        else:

          if($email == 1){
            $message = Helpers::setMessageValidationForm('Email');
          } elseif($dni == 1){
            $message = Helpers::setMessageValidationForm('DNI');
          }
          return back()->withInput()->with('message', $message);

        endif;

      } catch(\Exception $e) {
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $client = Client::find($id);
      $user   = User::find($client->id_user);
      
      return view('backend.client.edit', [
        'user'        => $user,
        'client'      => $client,
        'user_files'  => UserFile::where('user_id', $client->id_user)->get()
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $message;
      try {
        // Validations, check: email, dni
        $email  = User::where('email', $request->get('email'))->where('id', '!=', $request->get('id_user'))->count();
        $dni    = User::where('dni', $request->get('dni'))->where('id', '!=', $request->get('id_user'))->count();

        if($email == 0 && $dni == 0):

          // Update User
          $user               = User::find($request->get('id_user'));
          $user->name         = $request->get('name');
          $user->last_name    = $request->get('last_name');
          $user->email        = $request->get('email');
          $user->dni          = $request->get('dni');
          $user->telephone    = $request->get('telephone');
          $user->other_telephone = $request->get('other_telephone');
          $user->is_active    = $request->get('is_active');

          $user->is_client    = 1;
          $user->save();

          // Updated Client
          $client             = Client::find($id);
          $client->id_user    = $user->id;
          $client->contact_last_name  = $request->get('contact_last_name');
          $client->contact_name       = $request->get('contact_name');
          $client->contact_dni        = $request->get('contact_dni');
          $client->contact_telephone  = $request->get('contact_telephone');
          $client->contact_other_telephone = $request->get('contact_other_telephone');
          $client->contact_email      = $request->get('contact_email');

          // data billing
          $client->billing_razon_social = $request->get('billing_razon_social');
          $client->billing_cuit         = $request->get('billing_cuit');
          $client->billing_gross_income = $request->get('billing_gross_income');
          $client->billing_condition_iva= $request->get('billing_condition_iva');
          $client->billing_id_country   = $request->get('billing_id_country');
          $client->billing_id_province  = $request->get('billing_id_province');
          $client->billing_location     = $request->get('billing_location');
          $client->billing_address      = $request->get('billing_address');
          $client->billing_address_number = $request->get('billing_address_number');
          $client->billing_piso_depto   = $request->get('billing_piso_depto');
          $client->billing_cp           = $request->get('billing_cp');

          $client->billing_razon_social_2 = $request->get('billing_razon_social_2');
          $client->billing_cuit_2         = $request->get('billing_cuit_2');
          $client->billing_gross_income_2 = $request->get('billing_gross_income_2');
          $client->billing_condition_iva_2= $request->get('billing_condition_iva_2');
          $client->billing_id_country_2   = $request->get('billing_id_country_2');
          $client->billing_id_province_2  = $request->get('billing_id_province_2');
          $client->billing_location_2     = $request->get('billing_location_2');
          $client->billing_address_2      = $request->get('billing_address_2');
          $client->billing_address_number_2 = $request->get('billing_address_number_2');
          $client->billing_piso_depto_2   = $request->get('billing_piso_depto_2');
          $client->billing_cp_2           = $request->get('billing_cp_2');

          $client->save();

          User::uploadFiles($request, 'files', "$user->id");

          return redirect('/clients')->with('message', 'Cliente editado con exito!');

        else:

          if($email == 1){
            $message = Helpers::setMessageValidationForm('Email');
          } elseif($dni == 1){
            $message = Helpers::setMessageValidationForm('DNI');
          }
          return back()->withInput()->with('message', $message);

        endif;

      } catch(\Exception $e) {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $client = Client::find($id);
        // User Delete
        $user = User::find($client->id_user);
        $user->delete();      
      $client->delete();

      return response(array('message' => 'Cliente eliminado con exito!'));
    }
}
