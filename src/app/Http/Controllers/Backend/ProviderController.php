<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers;
use App\User;
use App\Provider;
use App\UserFile;
use App\Item;

class ProviderController extends Controller
{
    // protected $_permission;
    // protected $permissionApproved = ['1', '3'];

    // /**
    //  * Instantiate a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //   $this->middleware(function ($request, $next) {

    //     if(\Request::route()->getName() == "crew.store"){
    //       return $next($request);
    //     }

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
      return view('backend.provider.index', [
        'providers' => Provider::orderBy('id', 'DESC')->get()
      ]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
      return view('backend.provider.create', [
        'items' => Item::where('is_active', '1')->get()
      ]);
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
        // Validations, check: cuit
        $cuit  = User::where('cuit', $request->get('cuit'))->count();
        $email = User::where('email', $request->get('contact_email'))->count();
        $create_provider_guest = ($request->get('create_provider_guest') ? $request->get('create_provider_guest') : null);

        if($cuit == 0):
          // New User
          $user = new User;
          $user->name           = $request->get('name');
          $user->cuit           = $request->get('cuit');
          $user->id_country     = $request->get('id_country');
          $user->id_province    = $request->get('id_province');
          $user->location       = $request->get('location');
          $user->address        = $request->get('address');
          $user->address_number = $request->get('address_number');
          $user->cp             = $request->get('cp');
          $user->piso_depto     = $request->get('piso_depto');
          
          $user->email          = ($email > 0 ? $request->get('cuit') : $request->get('contact_email'));
          $user->password       = Hash::make('arq_'.$request->get('cuit'));
          $user->is_provider    = "1";
          $user->is_active      = $request->get('is_active');
          $user->save();

          // New Provider
          $provider                   = new Provider;
          $provider->id_user          = $user->id;
          $provider->id_category      = json_encode($request->get('id_category'));
          $provider->condition_iva    = $request->get('condition_iva');
          $provider->invoice_type     = $request->get('invoice_type');
          $provider->retains_gross_income           = $request->get('retains_gross_income');
          $provider->retains_gross_income_porcentage= $request->get('retains_gross_income_porcentage');

          // data contact
          $provider->contact_name     = $request->get('contact_name');
          $provider->contact_cargo    = $request->get('contact_cargo');
          $provider->contact_telephone= $request->get('contact_telephone');
          $provider->contact_email    = $request->get('contact_email');

          $provider->contact_name_2     = $request->get('contact_name_2');
          $provider->contact_cargo_2    = $request->get('contact_cargo_2');
          $provider->contact_telephone_2= $request->get('contact_telephone_2');
          $provider->contact_email_2    = $request->get('contact_email_2');

          $provider->contact_name_3     = $request->get('contact_name_3');
          $provider->contact_cargo_3    = $request->get('contact_cargo_3');
          $provider->contact_telephone_3= $request->get('contact_telephone_3');
          $provider->contact_email_3    = $request->get('contact_email_3');

          // data bank
          $provider->bank             = $request->get('bank');
          $provider->type_account     = $request->get('type_account');
          $provider->number_account   = $request->get('number_account');
          $provider->cbu              = $request->get('cbu');
          $provider->alias_account    = $request->get('alias_account');

          $provider->bank_2             = $request->get('bank_2');
          $provider->type_account_2     = $request->get('type_account_2');
          $provider->number_account_2   = $request->get('number_account_2');
          $provider->cbu_2              = $request->get('cbu_2');
          $provider->alias_account_2    = $request->get('alias_account_2');

          $provider->bank_3             = $request->get('bank_3');
          $provider->type_account_3     = $request->get('type_account_3');
          $provider->number_account_3   = $request->get('number_account_3');
          $provider->cbu_3              = $request->get('cbu_3');
          $provider->alias_account_3    = $request->get('alias_account_3');

          // data secure
          $provider->secure_number_poliza = $request->get('secure_number_poliza');
          if($create_provider_guest)
          {
            $provider->secure_expiration    = ($request->get('secure_expiration') ? $request->get('secure_expiration')." 00:00:00" : null);
          } else {
            $provider->secure_expiration    = Helpers::setDateFormatTimestamp($request->get('secure_expiration'));
          }     

          $provider->secure_number_poliza_2 = $request->get('secure_number_poliza_2');
          if($create_provider_guest)
          {
            $provider->secure_expiration_2    = ($request->get('secure_expiration_2') ? $request->get('secure_expiration_2')." 00:00:00" : null);
          } else {
            $provider->secure_expiration_2    = Helpers::setDateFormatTimestamp($request->get('secure_expiration_2'));
          }

          $provider->secure_number_poliza_3 = $request->get('secure_number_poliza_3');
          if($create_provider_guest)
          {
            $provider->secure_expiration_3    = ($request->get('secure_expiration_3') ? $request->get('secure_expiration_3')." 00:00:00" : null);
          } else {
            $provider->secure_expiration_3    = Helpers::setDateFormatTimestamp($request->get('secure_expiration_3'));
          }

          $provider->save();

          User::uploadFiles($request, 'files', "$user->id");

          if($create_provider_guest)
          {
            return back()->with('message', 'Proveedor creado con exito!');
          } else {
            return redirect('/providers')->with('message', 'Proveedor creado con exito!');
          }          
        else:

          if($cuit == 1){
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
      $provider = Provider::find($id);
      return view('backend.provider.show', [
        'provider' => $provider,
        'user_files' => UserFile::where('user_id', $provider->id_user)->get(),
        'user' => User::where('id', $provider->id_user)->first(),
        'items' => Item::where('is_active', '1')->get()
      ]);
    }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
    public function edit($id)
    {
      $provider = Provider::find($id);
      return view('backend.provider.edit', [
        'provider' => $provider,
        'user_files' => UserFile::where('user_id', $provider->id_user)->get(),
        'user' => User::where('id', $provider->id_user)->first(),
        'items' => Item::where('is_active', '1')->get()
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
        // Validations, check: cuit
        $email = User::where('email', $request->get('contact_email'))->count();
        $cuit   = User::where('cuit', $request->get('cuit'))->where('id', '!=', $request->get('id_user'))->count();

        if($cuit == 0):          
          // Update User
          $user = User::find($request->get('id_user'));
          $user->name           = $request->get('name');
          $user->cuit           = $request->get('cuit');
          $user->id_country     = $request->get('id_country');
          $user->id_province     = $request->get('id_province');
          $user->location       = $request->get('location');
          $user->address        = $request->get('address');
          $user->address_number = $request->get('address_number');
          $user->cp             = $request->get('cp');
          $user->piso_depto     = $request->get('piso_depto');

          $user->email          = ($email > 0 ? $request->get('cuit') : $request->get('contact_email'));
          $user->is_provider    = "1";
          $user->is_active      = $request->get('is_active');
          $user->save();
          
          // Update Provider
          $provider = Provider::find($id);
          $provider->id_user          = $user->id;
          $provider->id_category      = json_encode($request->get('id_category'));
          $provider->condition_iva    = $request->get('condition_iva');
          $provider->invoice_type     = $request->get('invoice_type');
          $provider->retains_gross_income           = $request->get('retains_gross_income');
          $provider->retains_gross_income_porcentage= $request->get('retains_gross_income_porcentage');

          // data contact
          $provider->contact_name     = $request->get('contact_name');
          $provider->contact_cargo    = $request->get('contact_cargo');
          $provider->contact_telephone= $request->get('contact_telephone');
          $provider->contact_email    = $request->get('contact_email');

          $provider->contact_name_2     = $request->get('contact_name_2');
          $provider->contact_cargo_2    = $request->get('contact_cargo_2');
          $provider->contact_telephone_2= $request->get('contact_telephone_2');
          $provider->contact_email_2    = $request->get('contact_email_2');

          $provider->contact_name_3     = $request->get('contact_name_3');
          $provider->contact_cargo_3    = $request->get('contact_cargo_3');
          $provider->contact_telephone_3= $request->get('contact_telephone_3');
          $provider->contact_email_3    = $request->get('contact_email_3');

          // data bank
          $provider->bank             = $request->get('bank');
          $provider->type_account     = $request->get('type_account');
          $provider->number_account   = $request->get('number_account');
          $provider->cbu              = $request->get('cbu');
          $provider->alias_account    = $request->get('alias_account');

          $provider->bank_2             = $request->get('bank_2');
          $provider->type_account_2     = $request->get('type_account_2');
          $provider->number_account_2   = $request->get('number_account_2');
          $provider->cbu_2              = $request->get('cbu_2');
          $provider->alias_account_2    = $request->get('alias_account_2');

          $provider->bank_3             = $request->get('bank_3');
          $provider->type_account_3     = $request->get('type_account_3');
          $provider->number_account_3   = $request->get('number_account_3');
          $provider->cbu_3              = $request->get('cbu_3');
          $provider->alias_account_3    = $request->get('alias_account_3');

          // data secure
          $provider->secure_number_poliza = $request->get('secure_number_poliza');
          $provider->secure_expiration    = Helpers::setDateFormatTimestamp($request->get('secure_expiration'));

          $provider->secure_number_poliza_2 = $request->get('secure_number_poliza_2');
          $provider->secure_expiration_2    = Helpers::setDateFormatTimestamp($request->get('secure_expiration_2'));

          $provider->secure_number_poliza_3 = $request->get('secure_number_poliza_3');
          $provider->secure_expiration_3    = Helpers::setDateFormatTimestamp($request->get('secure_expiration_3'));

          $provider->save();

          User::uploadFiles($request, 'files', $user->id);

          return redirect('/providers')->with('message', 'Proveedor editado con exito!');

        else:

          if($cuit == 1){
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
      $provider = Provider::find($id);
        // User Delete
        $user = User::find($provider->id_user);
        $user->delete();
      // Provider Delete
      $provider->delete();

      return response(array('message' => 'Proveedor eliminado con exito!'));
    }

  }