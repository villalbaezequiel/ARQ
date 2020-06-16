<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Project;
use App\ProjectDocument;
use App\ProjectBudget;
use App\ProjectRealCost;
use App\ProjectProvider;
use App\Client;
use App\Item;
use App\SubItem;

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
        $items      = [];
        $itemsParent= Item::select('id', 'name')->where('is_active', '1')->get();

        foreach($itemsParent as $item)
        {
            $subItems= SubItem::select('id', 'name')
                                ->where('is_active', '1')
                                ->where('id_item', $item->id)
                                ->get();
            if(count($subItems))
            {
                $items[] = [
                    'id'    => $item->id, 
                    'name'  => $item->name,
                    'data'  => $subItems
                ];
            }
        }

        $clients = Client::select('clients.id', 'users.last_name', 'users.name')->join('users', 'clients.id_user', 'users.id')->where('users.is_active', '1')->get();
        
        return view('backend.project.create', [
            'clients' => $clients,
            'items'   => $items
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
            // $project->is_open_budget= $request->get('is_open_budget');
            $project->is_open_budget= '1';
            $project->is_active     = $request->get('is_active');
            $project->link_google_drive = $request->get('link_google_drive');
            $project->link_dropbox      = $request->get('link_dropbox');
           $project->save();

            // save budgets
            if($project->getIsOpenBudget())
            {
                if($request->get('budget'))
                {
                    foreach($request->get('budget') as $key => $val)
                    {
                        $projectBudget = new ProjectBudget;
                        $projectBudget->id_project  = $project->id;
                        $projectBudget->id_item     = $val['id_item'];
                        $projectBudget->id_subitem  = $key;
                        $projectBudget->unit        = $val['unit'];
                        $projectBudget->quantity    = $val['quantity'];
                        $projectBudget->unit_cost   = $val['unit_cost'];
                        $projectBudget->subtotal    = $val['subtotal'];
                        $projectBudget->tax         = $val['tax'];
                        $projectBudget->total       = $val['total'];
                        $projectBudget->save();
                    }
                }
            }

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
        // collection Items(Rubros) and SubItems(SubRubros)
        $items      = [];
        $itemsParent= Item::select('id', 'name')->where('is_active', '1')->get();

        foreach($itemsParent as $item)
        {
            $subItems= SubItem::select('id', 'name')
                                ->where('is_active', '1')
                                ->where('id_item', $item->id)
                                ->get();
            $items[] = [
                'id'    => $item->id, 
                'name'  => $item->name,
                'data'  => $subItems
            ];
        }
        
        // collection Budgets (presupuesto) from Project
        $collectionProjectBudget= [];
        $projectBudget          = ProjectBudget::where('id_project', $id)->get();

        foreach ($projectBudget as $value) {
            $collectionProjectBudget[$value->id_item][] = $value;
        }

        // collection ProjectRealCost
        $collectionProjectRealCost  = [];
        $projectRealCost            = ProjectRealCost::where('id_project', $id)->get();

        foreach ($projectRealCost as $value) {
            $collectionProjectRealCost[$value->id_item][] = $value;
        }

        // collection Documents from Project
        $documents              = ProjectDocument::where('id_project', $id)->get();
        
        // collection clients
        $clients                = Client::select('clients.id', 'users.last_name', 'users.name')->join('users', 'clients.id_user', 'users.id')->where('users.is_active', '1')->get();

        // collection ProjectProviders
        $collectionProjecProviders  = ProjectProvider::where('id_project', $id)->get();

        return view('backend.project.edit', [
            'project'           => Project::find($id),
            'clients'           => $clients,
            'items'             => $items,
            'project_real_costs'=> $collectionProjectRealCost,
            'project_budgets'   => $collectionProjectBudget,
            'project_documents' => $documents,
            'project_providers' => $collectionProjecProviders
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

            if($project->getIsOpenBudget())
            {
                if($request->get('budget'))
                {
                    foreach($request->get('budget') as $key => $val)
                    {
                        if(isset($val['id']))
                        {
                            $projectBudget = ProjectBudget::find($val['id']);
                            $projectBudget->id_subitem  = $key;
                            $projectBudget->unit        = $val['unit'];
                            $projectBudget->quantity    = $val['quantity'];
                            $projectBudget->unit_cost   = $val['unit_cost'];
                            $projectBudget->subtotal    = $val['subtotal'];
                            $projectBudget->tax         = $val['tax'];
                            $projectBudget->total       = $val['total'];
                            $projectBudget->save();
                        } else {
                            $projectBudget = new ProjectBudget;
                            $projectBudget->id_project  = $project->id;
                            $projectBudget->id_item     = $val['id_item'];
                            $projectBudget->id_subitem  = $key;
                            $projectBudget->unit        = $val['unit'];
                            $projectBudget->quantity    = $val['quantity'];
                            $projectBudget->unit_cost   = $val['unit_cost'];
                            $projectBudget->subtotal    = $val['subtotal'];
                            $projectBudget->tax         = $val['tax'];
                            $projectBudget->total       = $val['total'];
                            $projectBudget->save();
                        }
                    }
                }
            }

            $projectProviders = $request->get('project_providers');

            if($projectProviders)
            {
                foreach($projectProviders as $id_project_real_cost => $providers)
                {
                    foreach($providers as $id_provider)
                    {
                        $checkProjectProviders = ProjectProvider::where('id_project_real_cost', $id_project_real_cost)
                                                                ->where('id_project', $project->id)
                                                                ->where('id_provider', $id_provider)
                                                                ->count();
                        if($checkProjectProviders == 0)
                        {
                            $projectProvider = new ProjectProvider;
                            $projectProvider->id_project            = $project->id;
                            $projectProvider->id_project_real_cost  = $id_project_real_cost;
                            $projectProvider->id_provider           = $id_provider;
                            $projectProvider->save();
                        }
                    }
                }
            }

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
