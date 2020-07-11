<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Project;
use App\ProjectDocument;
use App\ProjectBudget;
use App\ProjectRealCost;
use App\ProjectCashFlow;
use App\Client;
use App\User;
use App\Provider;
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
            $project->is_open_budget= '1';
            $project->is_active     = $request->get('is_active');
            $project->link_google_drive = $request->get('link_google_drive');
            $project->link_dropbox  = $request->get('link_dropbox');
            $project->name_budget   = $request->get('name_budget');
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
                        $projectBudget->unit_cost   = Project::setParseFormatPrice($val['unit_cost']);
                        $projectBudget->subtotal    = Project::setParseFormatPrice($val['subtotal']);
                        $projectBudget->tax         = Project::setParseFormatTax($val['tax']);
                        $projectBudget->total       = Project::setParseFormatPrice($val['total']);
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
        // this Project
        $project    = Project::find($id);

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
        
        // collection ProjectBudget (presupuesto)
        $collectionProjectBudget= [];
        $projectBudget          = ProjectBudget::select("project_budgets.*")
                                                    ->join('items', 'items.id', 'project_budgets.id_item')
                                                    ->join('sub_items', 'sub_items.id', 'project_budgets.id_subitem')
                                                    ->where('id_project', $id)
                                                    ->orderBy('items.number', 'ASC')
                                                    ->orderBy('sub_items.number', 'ASC')
                                                    ->get();

        foreach ($projectBudget as $value) {
            $collectionProjectBudget[$value->id_item][] = $value;
        }

        // collection ProjectItems (flujo de caja -> Rubros)
        $collectionProjectItems  = [];

        // collection ProjectRealCost (seguimiento)
        $collectionProjectRealCost  = [];
        $projectRealCost            = ProjectRealCost::select('project_real_costs.*')
                                                    ->join('items', 'items.id', 'project_real_costs.id_item')
                                                    ->join('sub_items', 'sub_items.id', 'project_real_costs.id_subitem')
                                                    ->where('id_project', $id)
                                                    ->orderBy('items.number', 'ASC')
                                                    ->orderBy('sub_items.number', 'ASC')
                                                    ->get();

        foreach ($projectRealCost as $value)
        {
            $collectionProjectRealCost[$value->id_item][]   = $value;
            $collectionProjectItems[$value->id_item]        = Item::getName($value->id_item);
        }

        // collection ProjectDocument (documentos)
        $collectionDocuments        = ProjectDocument::where('id_project', $id)->get();
        
        // collection clients
        $collectionClients          = Client::select('clients.id', 'users.last_name', 'users.name')
                                        ->join('users', 'clients.id_user', 'users.id')
                                        ->where('users.is_active', '1')->get();

        // collection ProjectClients (flujo de caja -> clientes del proyecto)
        $collectionProjectClients   = Client::select('clients.id', 'users.last_name', 'users.name')
                                        ->join('users', 'clients.id_user', 'users.id')
                                        ->whereIn('clients.id', json_decode($project->id_client, true))->get();

        // collection ProjectCashFlow (flujo de caja)
        $collectionProjectCashFlow  = ProjectCashFlow::where('id_project', $id)->get();

        $balance = 0;

        foreach ($collectionProjectCashFlow as &$value)
        {
            if($value->type == "entry") {
                $balance += $value->amount;
            } elseif($value->type == "egress") {
                $balance -= $value->amount;
            }

            $value->date    = Helpers::getDateParseFormat($value->date);
            $value->type    = ProjectCashFlow::getType($value->type);
            $value->balance = Helpers::getFormatSalary(round($balance, 2));
            $value->amount  = Helpers::getFormatSalary($value->amount);
            $value->tax     = $value->tax;
            $value->payment_type = ProjectCashFlow::getPaymentType($value->payment_type);
            $value->id_item = Item::getName($value->id_item);
            $value->id_subitem  = SubItem::getName($value->id_subitem);
            $value->detail  = ProjectCashFlow::getDetail($value->detail);
            $value->id_provider = Provider::getName($value->id_provider);
            $value->id_user     = User::getFullName($value->id_user);
            $value->id_client   = Client::getName($value->id_client);
        }

        return view('backend.project.edit', [
            'project'           => $project,
            'clients'           => $collectionClients,
            'items'             => $items,
            'project_clients'   => $collectionProjectClients,
            'project_items'     => $collectionProjectItems,
            'project_real_costs'=> $collectionProjectRealCost,
            'project_budgets'   => $collectionProjectBudget,
            'project_documents' => $collectionDocuments,
            'project_cash_flow' => $collectionProjectCashFlow,
            'project_cash_flow_balance_total' => $balance
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
            $project->name_budget   = $request->get('name_budget');
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
                            $projectBudget->unit_cost   = Project::setParseFormatPrice($val['unit_cost']);
                            $projectBudget->subtotal    = Project::setParseFormatPrice($val['subtotal']);
                            $projectBudget->tax         = Project::setParseFormatTax($val['tax']);
                            $projectBudget->total       = Project::setParseFormatPrice($val['total']);
                            $projectBudget->save();
                        } else {
                            $projectBudget = new ProjectBudget;
                            $projectBudget->id_project  = $project->id;
                            $projectBudget->id_item     = $val['id_item'];
                            $projectBudget->id_subitem  = $key;
                            $projectBudget->unit        = $val['unit'];
                            $projectBudget->quantity    = $val['quantity'];
                            $projectBudget->unit_cost   = Project::setParseFormatPrice($val['unit_cost']);
                            $projectBudget->subtotal    = Project::setParseFormatPrice($val['subtotal']);
                            $projectBudget->tax         = Project::setParseFormatTax($val['tax']);
                            $projectBudget->total       = Project::setParseFormatPrice($val['total']);
                            $projectBudget->save();
                        }
                    }
                }
            } else {
                if($request->get('real_cost'))
                {
                    foreach($request->get('real_cost') as $key => $val)
                    {
                        if(isset($val['id']))
                        {
                            $projectRealCost = ProjectRealCost::find($val['id']);
                            $projectRealCost->id_subitem   = $key;
                            $projectRealCost->quantity     = $val['quantity'];
                            $projectRealCost->unit_cost    = Project::setParseFormatPrice($val['unit_cost']);
                            $projectRealCost->tax          = Project::setParseFormatTax($val['tax']);
                            $projectRealCost->budget_total = Project::setParseFormatPrice($val['budget_total']);
                            $projectRealCost->balance_item = Project::setParseFormatPrice($val['balance_item']);
                            $projectRealCost->budget_variation_pesos   = Project::setParseFormatPrice($val['budget_variation_pesos']);
                            $projectRealCost->save();

                        } else {
                            $projectRealCost = new ProjectRealCost;
                            $projectRealCost->id_project   = $project->id;
                            $projectRealCost->id_item      = $val['id_item'];
                            $projectRealCost->id_subitem   = $key;
                            $projectRealCost->quantity     = $val['quantity'];
                            $projectRealCost->unit_cost    = Project::setParseFormatPrice($val['unit_cost']);
                            $projectRealCost->tax          = Project::setParseFormatTax($val['tax']);
                            $projectRealCost->budget_total = Project::setParseFormatPrice($val['budget_total']);
                            $projectRealCost->paid         = 0;
                            $projectRealCost->balance_paid = 0;
                            $projectRealCost->balance_item = Project::setParseFormatPrice($val['balance_item']);
                            $projectRealCost->budget_variation_pesos   = Project::setParseFormatPrice($val['budget_variation_pesos']);
                            $projectRealCost->budget_variation_tax     = 0;
                            $projectRealCost->save();
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
        // delete Project
        $project = Project::find($id);
        $project->delete();

        // delete ProjectBudget
        $projectBudget = ProjectBudget::where('id_project' ,$id);
        $projectBudget->delete();

        // delete ProjectRealCost
        $projectRealCost = ProjectRealCost::where('id_project' ,$id);
        $projectRealCost->delete();

        // delete ProjectDocument
        $projectDocument = ProjectDocument::where('id_project', $id);
        $projectDocument->delete();

        // delete ProjectCashFlow
        $projectCashFlow = ProjectCashFlow::where('id_project' ,$id);
        $projectCashFlow->delete();
     
        return response(array('message' => 'Proyecto eliminado con exito!'));
    }
}
