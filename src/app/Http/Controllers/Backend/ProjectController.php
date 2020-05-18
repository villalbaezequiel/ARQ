<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Project;
use App\ProjectDocument;
use App\Client;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.project.index', [
            'projects' => Project::orderBy('id', 'DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::select('clients.id', 'users.last_name', 'users.name')->join('users', 'clients.id_user', 'users.id')->where('users.is_active', '1')->get();
        return view('backend.project.create', [
            'clients' => $clients
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
            // New Project
            $project                = new Project;
            $project->start_date    = Project::getDateParseFormat($request->get('start_date'));
            $project->finish_date   = Project::getDateParseFormat($request->get('finish_date'));
            $project->id_client     = json_encode($request->get('id_client'));
            $project->name          = $request->get('name');
            $project->description   = $request->get('description');
            $project->observations  = $request->get('observations');
            $project->id_country    = $request->get('id_country');
            $project->id_province   = $request->get('id_province');
            $project->location      = $request->get('location');
            $project->address       = $request->get('address');
            $project->address_number= $request->get('address_number');
            $project->cp            = $request->get('cp');
            $project->piso_depto    = $request->get('piso_depto');
            $project->is_active     = $request->get('is_active');
            $project->link_google_drive = $request->get('link_google_drive');
            $project->link_dropbox      = $request->get('link_dropbox');
            $project->save();

            //save ProductDocument
            Project::uploadDocuments($request, 'files', $project->id);

            return redirect('/projects')->with('message', 'Proyecto creado con exito!');

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
        $clients = Client::select('clients.id', 'users.last_name', 'users.name')->join('users', 'clients.id_user', 'users.id')->where('users.is_active', '1')->get();
        return view('backend.project.edit', [
            'project'           => Project::find($id),
            'clients'           => $clients,
            'project_documents' => ProjectDocument::where('id_project', $id)->get()
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
            // New Project
            $project                = Project::find($id);
            $project->start_date    = Project::getDateParseFormat($request->get('start_date'));
            $project->finish_date   = Project::getDateParseFormat($request->get('finish_date'));
            $project->id_client     = json_encode($request->get('id_client'));
            $project->name          = $request->get('name');
            $project->description   = $request->get('description');
            $project->observations  = $request->get('observations');
            $project->id_country    = $request->get('id_country');
            $project->id_province   = $request->get('id_province');
            $project->location      = $request->get('location');
            $project->address       = $request->get('address');
            $project->address_number= $request->get('address_number');
            $project->cp            = $request->get('cp');
            $project->piso_depto    = $request->get('piso_depto');
            $project->is_active     = $request->get('is_active');
            $project->link_google_drive = $request->get('link_google_drive');
            $project->link_dropbox      = $request->get('link_dropbox');
            $project->save();

            //save ProductDocument
            Project::uploadDocuments($request, 'files', $project->id);

            return redirect('/projects')->with('message', 'Proyecto editado con exito!');

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
        $project = Project::find($id);
        $project->delete();
     
        return response(array('message' => 'Proyecto eliminado con exito!'));
    }
}
