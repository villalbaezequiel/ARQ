<?php


namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Helpers;
use App\User;
use App\Client;

class ClientController extends Controller
{
    protected $_permission;
    protected $permissionApproved = ['1', '4'];

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware(function ($request, $next) {
        $cancelPermission = true;
        $this->_permission = json_decode(Auth::user()->permissions);

        if($this->_permission){
          foreach($this->permissionApproved as $approved){
            foreach($this->_permission as $userPermission){
              if($approved == $userPermission){
                $cancelPermission = false;
                return $next($request);
              }
            }
          }
        }

      });
    }

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
        // Validations, check: email, dni, cuit
        $email = User::where('email', $request->get('email'))->count();
        $dni   = User::where('dni', $request->get('dni'))->count();
        $cuit  = User::where('cuit', $request->get('cuit'))->count();
                
        if($email == 0 && $dni == 0 && $cuit == 0):

          // add format birthdate
          $birthdate = explode('/', $request->get('birthdate'));

          // Update User
          $user = new User;
          $user->name         = $request->get('name');
          $user->last_name    = $request->get('last_name');
          $user->email        = $request->get('email');
          $user->type_dni     = $request->get('type_dni');
          $user->dni          = $request->get('dni');
          $user->cuit         = $request->get('cuit');
          $user->gender       = $request->get('gender');
          $user->civil_status = $request->get('civil_status');
          $user->birthdate    = (isset($birthdate[2]) ? $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0] : $request->get('birthdate'));
          $user->id_study     = $request->get('id_study');
          $user->id_nationality = $request->get('id_nationality');
          $user->id_country   = $request->get('id_country');
          $user->visa         = $request->get('visa');
          $user->location     = $request->get('location');
          $user->address      = $request->get('address');
          $user->address_number = $request->get('address_number');
          $user->piso_depto   = $request->get('piso_depto');
          $user->cp           = $request->get('cp');
          $user->telephone    = $request->get('telephone');
          $user->other_telephone = $request->get('other_telephone');
          $user->is_client    = 1;
          $user->is_active    = $request->get('is_active');
          $user->save();

          // New Client
          $client = new Client;
          $client->id_user = $user->id;
          $client->nick = $request->get('nick');
          $client->razon_social = $request->get('razon_social');
          $client->contact_name = $request->get('contact_name');
          $client->contact_email = $request->get('contact_email');
          $client->contact_telephone = $request->get('contact_telephone');
          $client->retencion_ganancia = $request->get('retencion_ganancia');
          $client->iibb = $request->get('iibb');
          $client->iva = $request->get('iva');
          $client->save();

          return redirect('/clients')->with('message', 'Cliente creado con exito!');

        else:

          if($email == 1){
            $message = Helpers::setMessageValidationForm('Email');
          } elseif($dni == 1){
            $message = Helpers::setMessageValidationForm('DNI');
          } else {
            $message = Helpers::setMessageValidationForm('CUIT');
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
      return view('backend.client.edit', [
        'client' => $client
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
        // Validations, check: email, dni, cuit
        $email  = User::where('email', $request->get('email'))->where('id', '!=', $request->get('id_user'))->count();
        $dni    = User::where('dni', $request->get('dni'))->where('id', '!=', $request->get('id_user'))->count();
        $cuit   = User::where('cuit', $request->get('cuit'))->where('id', '!=', $request->get('id_user'))->count();

        if($email == 0 && $dni == 0 && $cuit == 0):

          // add format birthdate
          $birthdate = explode('/', $request->get('birthdate'));

          // Update User
          $user = User::find($request->get('id_user'));
          $user->name         = $request->get('name');
          $user->last_name    = $request->get('last_name');
          $user->email        = $request->get('email');
          $user->type_dni     = $request->get('type_dni');
          $user->dni          = $request->get('dni');
          $user->cuit         = $request->get('cuit');
          $user->gender       = $request->get('gender');
          $user->civil_status = $request->get('civil_status');
          $user->birthdate    = (isset($birthdate[2]) ? $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0] : $request->get('birthdate'));
          $user->id_study     = $request->get('id_study');
          $user->id_nationality = $request->get('id_nationality');
          $user->id_country   = $request->get('id_country');
          $user->visa         = $request->get('visa');
          $user->location     = $request->get('location');
          $user->address      = $request->get('address');
          $user->address_number = $request->get('address_number');
          $user->piso_depto   = $request->get('piso_depto');
          $user->cp           = $request->get('cp');
          $user->telephone    = $request->get('telephone');
          $user->other_telephone = $request->get('other_telephone');
          $user->is_client    = 1;
          $user->is_active    = $request->get('is_active');
          $user->save();

          // New Client
          $client = Client::find($id);
          $client->id_user = $user->id;
          $client->nick = $request->get('nick');
          $client->razon_social = $request->get('razon_social');
          $client->contact_name = $request->get('contact_name');
          $client->contact_email = $request->get('contact_email');
          $client->contact_telephone = $request->get('contact_telephone');
          $client->retencion_ganancia = $request->get('retencion_ganancia');
          $client->iibb = $request->get('iibb');
          $client->iva = $request->get('iva');
          $client->save();

          return redirect('/clients')->with('message', 'Cliente editado con exito!');

        else:

          if($email == 1){
            $message = Helpers::setMessageValidationForm('Email');
          } elseif($dni == 1){
            $message = Helpers::setMessageValidationForm('DNI');
          } else {
            $message = Helpers::setMessageValidationForm('CUIT');
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
