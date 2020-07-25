<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Provider;
use App\Project;
use App\ProjectBudget;
use App\ProjectRealCost;
use App\ProjectCashFlow;
use App\Item;
use App\SubItem;
use DB;

class JsonController extends Controller
{
    public function deleteBudgetItem(Request $request)
    {
        $status = false;
        
        if($request->get('id_project') && $request->get('id_item'))
        {
            try {

                $projectBudget = ProjectBudget::where('id_item', $request->get('id_item'))
                                                ->where('id_project', $request->get('id_project'));

                $projectBudget->delete();

                $status = true;
                return response()->json(['status' => $status]);

            } catch (\Exception $err) {
                return response()->json([ 'status' => $status, 'message' => $err->getMessage() ]);
            }
        } else {
            return response()->json(['status' => $status]);
        }
    }

    // remove subitem from ProjectBudget ID
    public function deleteBudgetSubitem(Request $request)
    {
        $status = false;
        
        if($request->get('id'))
        {
            try {

                $projectBudget = ProjectBudget::find($request->get('id'));
                $projectBudget->delete();
                
                $status = true;
                return response()->json(['status' => $status]);

            } catch (\Exception $err) {
                return response()->json([ 'status' => $status, 'message' => $err->getMessage() ]);
            }
        } else {
            return response()->json(['status' => $status]);
        }
    }

    public function setOpenBudget(Request $request)
    {
        $status = false;
        
        if($request->get('id_project'))
        {
            try {

                // set is_open_budget
                $project = Project::find($request->get('id_project'));
                $project->is_open_budget = '0';
                $project->save();

                // insert items from project_real_costs
                $budgets = ProjectBudget::where('id_project', $project->id)->get();

                foreach($budgets as $budget)
                {   
                    $realCost = new ProjectRealCost;
                    $realCost->id_project   = $project->id;
                    $realCost->id_item      = $budget->id_item;
                    $realCost->id_subitem   = $budget->id_subitem;
                    $realCost->quantity     = $budget->quantity  ;
                    $realCost->unit_cost    = $budget->unit_cost;
                    $realCost->tax          = $budget->tax;
                    $realCost->budget_total = $budget->total;
                    $realCost->paid         = 0;
                    $realCost->balance_paid = 0;
                    $realCost->balance_item = 0;
                    $realCost->budget_variation_pesos   = $realCost->budget_total - $budget->total;
                    $realCost->budget_variation_tax     = $realCost->budget_variation_pesos / $budget->total;
                    $realCost->save();
                }

                $status = true;
                return response()->json(['status' => $status]);

            } catch (\Exception $err) {
                return response()->json([ 'status' => $status, 'message' => $err->getMessage() ]);
            }
        } else {
            return response()->json(['status' => $status]);
        }
    }

    public function deleteRealCostItem(Request $request)
    {
        $status = false;
        
        if($request->get('id_project') && $request->get('id_item'))
        {
            try {

                $projectRealCost = ProjectRealCost::where('id_item', $request->get('id_item'))
                                                ->where('id_project', $request->get('id_project'));

                $projectRealCost->delete();

                $status = true;
                return response()->json(['status' => $status]);

            } catch (\Exception $err) {
                return response()->json([ 'status' => $status, 'message' => $err->getMessage() ]);
            }
        } else {
            return response()->json(['status' => $status]);
        }
    }

    // remove subitem from ProjectRealCost ID
    public function deleteRealCostSubitem(Request $request)
    {
        $status = false;
        
        if($request->get('id'))
        {
            try {

                $projectRealCost = ProjectRealCost::find($request->get('id'));
                $projectRealCost->delete();
                
                $status = true;
                return response()->json(['status' => $status]);

            } catch (\Exception $err) {
                return response()->json([ 'status' => $status, 'message' => $err->getMessage() ]);
            }
        } else {
            return response()->json(['status' => $status]);
        }
    }

    public function getProvidersFromItem($idItem)
    {
        if($idItem)
        {
            try {
                $data = Provider::select('providers.id', 'users.name', 'providers.id_items')
                            ->where('id_items', 'like', "%$idItem%")
                            ->join('users', 'users.id', '=', 'providers.id_user')
                            ->get()->toArray();

                $result = [];

                foreach($data as $value)
                {
                    $remove = true;
                    foreach(json_decode($value['id_items']) as $id_item)
                    {      
                        if($id_item == $idItem) $remove = false;
                    }

                    if(!$remove) $result[] = $value;
                }
                            
                return response()->json(['data' => $result]);

            } catch (\Exception $err) {
                return response()->json([ 'data' => null ]);
            }
        } else {
            return response()->json(['data' => null]);
        }
    }

    public function getSubItemsFromItems(Request $request)
    {
        if($request->get('items'))
        {
            try {
                $data = [];

                foreach ($request->get('items') as $item)
                {
                    $data[Item::getName($item)] = SubItem::where('id_item', $item)->get()->toArray();
                }
                
                return response()->json(['data' => $data]);

            } catch (\Exception $err) {
                return response()->json([ 'data' => null ]);
            }
        } else {
            return response()->json(['data' => null]);
        }
    }

    public function searchProviders($idProject, $idItem)
    {
        if($idProject && $idItem)
        {
            try {
                $providersHistory = ProjectCashFlow::select('project_cash_flows.id', 'project_cash_flows.id_provider', DB::raw('SUM(project_cash_flows.amount) as amount'), 'users.name')
                                                    ->join('providers', 'providers.id', '=', 'project_cash_flows.id_provider')
                                                    ->join('users', 'users.id', '=', 'providers.id_user')
                                                    ->where('project_cash_flows.id_project', $idProject)
                                                    ->where('project_cash_flows.id_item', $idItem)
                                                    ->where('project_cash_flows.type', 'egress')
                                                    ->groupBy('project_cash_flows.id_provider')
                                                    ->orderBy('project_cash_flows.id', 'DESC')
                                                    ->get();

                foreach ($providersHistory as &$value) {
                    $value->amount = Helpers::getFormatSalary($value->amount);
                }
                
                return response()->json(['data' => $providersHistory]);

            } catch (\Exception $err) {
                return response()->json([ 'data' => null ]);
            }
        } else {
            return response()->json(['data' => null]);
        }
    }

    public function getHistoryProvider($idProject, $idProvider)
    {
        if($idProject && $idProvider)
        {
            try {
                
                $total = 0;

                $providerHistory = ProjectCashFlow::select('project_cash_flows.*', 'users.name')
                                                    ->join('providers', 'providers.id', '=', 'project_cash_flows.id_provider')
                                                    ->join('users', 'users.id', '=', 'providers.id_user')
                                                    ->where('project_cash_flows.id_project', $idProject)
                                                    ->where('project_cash_flows.id_provider', $idProvider)
                                                    ->where('project_cash_flows.type', 'egress')
                                                    ->orderBy('project_cash_flows.created_at', 'ASC')
                                                    ->get();
                
                foreach($providerHistory as &$provider)
                {
                    $total += $provider->amount;

                    $provider->date     = Helpers::getDateParseFormat($provider->date);
                    $provider->amount   = Helpers::getFormatSalary($provider->amount);
                    $provider->tax      = "%".$provider->tax;
                    $provider->payment_type = ProjectCashFlow::getPaymentType($provider->payment_type);
                    $provider->id_item  = Item::getName($provider->id_item);
                    $provider->id_subitem   = SubItem::getName($provider->id_subitem);
                    $provider->detail   = ProjectCashFlow::getDetail($provider->detail);
                }
                
                return response()->json(['data' => $providerHistory, 'total' => Helpers::getFormatSalary($total)]);

            } catch (\Exception $err) {
                return response()->json([ 'data' => null ]);
            }
        } else {
            return response()->json(['data' => null]);
        }
    }

    public function checkCashFlow()
    {
        try {
            $countCashFlow = ProjectCashFlow::where('is_approved', '0')->count();

            return response()->json(['status' => ($countCashFlow > 0 ? true : false)]);
        } catch (\Exception $err) {
            return response()->json(['status' => false]);
        }
    }

}
