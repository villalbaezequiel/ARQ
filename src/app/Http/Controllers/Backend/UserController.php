<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Helpers;
use App\User;

class UserController extends Controller
{
    protected $helpers;

    public function __construct()
    {
      $this->helpers = new Helpers;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.user.index', [
            'users' => User::orderBy('id', 'DESC')
                      ->where('is_client', null)
                      ->where('is_provider', null)
                      ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.user.create');
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
            $user->telephone    = $request->get('telephone');
            $user->other_telephone  = $request->get('other_telephone');
            $user->type_dni     = $request->get('type_dni');
            $user->dni          = $request->get('dni');
            $user->cuit         = $request->get('cuit');
            $user->gender       = $request->get('gender');
            $user->civil_status = $request->get('civil_status');
            $user->birthdate    = $this->helpers->setDateFormat($request->get('birthdate'));
            $user->id_country   = $request->get('id_country');
            $user->id_province  = $request->get('id_province');
            $user->location     = $request->get('location');
            $user->address      = $request->get('address');
            $user->address_number   = $request->get('address_number');
            $user->piso_depto   = $request->get('piso_depto');
            $user->cp           = $request->get('cp');

            // $user->id_study     = $request->get('id_study');
            // $user->id_nationality   = $request->get('id_nationality');
            // $user->visa         = $request->get('visa');

            $user->permissions  = $request->get('permissions');
            $user->password     = Hash::make($request->get('password'));
            $user->is_active    = $request->get('is_active');
            $user->save();

            return redirect('/users')->with('message', 'Usuario creado con exito!');
  
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
      return view('backend.user.show', [
        'user' => User::find($id)
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
      return view('backend.user.edit', [
          'user' => User::find($id)
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
          $email = User::where('email', $request->get('email'))->where('id', '!=', $id)->count();
          $dni   = User::where('dni', $request->get('dni'))->where('id', '!=', $id)->count();
                  
          if($email == 0 && $dni == 0):
  
            // new User
            $user               = User::find($id);
            $user->name         = $request->get('name');
            $user->last_name    = $request->get('last_name');
            $user->email        = $request->get('email');
            $user->telephone    = $request->get('telephone');
            $user->other_telephone  = $request->get('other_telephone');
            $user->type_dni     = $request->get('type_dni');
            $user->dni          = $request->get('dni');
            $user->cuit         = $request->get('cuit');
            $user->gender       = $request->get('gender');
            $user->civil_status = $request->get('civil_status');
            $user->birthdate    = $this->helpers->setDateFormat($request->get('birthdate'));
            $user->id_country   = $request->get('id_country');
            $user->id_province  = $request->get('id_province');
            $user->location     = $request->get('location');
            $user->address      = $request->get('address');
            $user->address_number   = $request->get('address_number');
            $user->piso_depto   = $request->get('piso_depto');
            $user->cp           = $request->get('cp');

            // $user->id_study     = $request->get('id_study');
            // $user->id_nationality   = $request->get('id_nationality');
            // $user->visa         = $request->get('visa');

            $user->permissions  = $request->get('permissions');
            if($request->get('password')){
              $user->password = Hash::make($request->get('password'));
            }
            $user->is_active    = $request->get('is_active');
            $user->save();

            return redirect('/users')->with('message', 'Usuario editado con exito!');
  
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
        // User Delete
        $user = User::find($id);
        $user->delete();
  
        return response(array('message' => 'Usuario eliminado con exito!'));
    }
}
