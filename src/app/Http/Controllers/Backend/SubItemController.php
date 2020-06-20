<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Item;
use App\SubItem;

class SubItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.sub_item.index', [
            'sub_items' => SubItem::orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.sub_item.create', [
            'items' => Item::all()
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
        try {
            $itemID             = $request->get('id_item');
            $getNumberItem      = Item::getNumberFromId($itemID);
            $lastNumberSubItem  = SubItem::select('number')->where('id_item', $itemID)->orderBy('number', 'DESC')->first();

            // New SubItem
            $sub_item               = new SubItem;
            $sub_item->name         = $request->get('name');
            $sub_item->id_item      = $itemID;
            $sub_item->number_parent= $getNumberItem;
            $sub_item->number       = ($lastNumberSubItem->number + 1);
            $sub_item->is_active    = $request->get('is_active');
            $sub_item->save();

            return redirect('/sub_items')->with('message', 'Sub Rubro creado con exito!');

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
        return view('backend.sub_item.edit', [
            'items' => Item::all(),
            'sub_item' => SubItem::find($id)
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
            // Updated SubItem
            $sub_item           = SubItem::find($id);
            $sub_item->name     = $request->get('name');

            $itemID = $request->get('id_item');

          if((int)$sub_item->id_item != (int)$itemID)
          {
            $getNumberItem      = Item::getNumberFromId($itemID);
            $lastNumberSubItem  = SubItem::select('number')->where('id_item', $itemID)->orderBy('number', 'DESC')->first();

            $sub_item->id_item          = $itemID;
            $sub_item->number_parent    = $getNumberItem;
            $sub_item->number           = ($lastNumberSubItem->number + 1);
          }

            $sub_item->is_active= $request->get('is_active');
            $sub_item->save();

            return redirect('/sub_items')->with('message', 'Sub Rubro editado con exito!');

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
        $sub_item = SubItem::find($id);
        $sub_item->delete();
     
        return response(array('message' => 'Sub Rubro eliminado con exito!'));
    }
}
