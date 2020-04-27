<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.item.index', [
            'items' => Item::orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // New Item
            $item           = new Item;
            $item->name     = $request->get('name');
            $item->is_active= $request->get('is_active');
            $item->save();

            return redirect('/items')->with('message', 'Rubro creado con exito!');

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
        return view('backend.item.edit', [
            'item' => Item::find($id)
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
        try {
            // Updated Item
            $item           = Item::find($id);
            $item->name     = $request->get('name');
            $item->is_active= $request->get('is_active');
            $item->save();

            return redirect('/items')->with('message', 'Rubro editado con exito!');

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
        $item = Item::find($id);
        $item->delete();
     
        return response(array('message' => 'Rubro eliminado con exito!'));
    }
}
