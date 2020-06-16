<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Provider;
use App\Project;
use App\ProjectBudget;
use App\ProjectRealCost;
use App\ProjectProvider;

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
                    $realCost->balance_item = $budget->total;
                    $realCost->budget_variation_pesos   = $budget->total;
                    $realCost->budget_variation_tax     = 0;
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

    public function getProvidersFromSubRubro($idSubRubro)
    {
        if($idSubRubro)
        {
            try {
                $data = Provider::select('providers.id', 'users.name')
                            ->where('id_category', 'like', "%$idSubRubro%")
                            ->join('users', 'users.id', '=', 'providers.id_user')
                            ->get()->toArray();
                
                return response()->json(['data' => $data]);

            } catch (\Exception $err) {
                return response()->json([ 'data' => null ]);
            }
        } else {
            return response()->json(['data' => null]);
        }
    }

    // remove subitem from ProjectProvider ID
    public function deleteProjectProvider(Request $request)
    {
        $status = false;
        
        if($request->get('id'))
        {
            try {

                $projectProvider = ProjectProvider::find($request->get('id'));
                $projectProvider->delete();
                
                $status = true;
                return response()->json(['status' => $status]);

            } catch (\Exception $err) {
                return response()->json([ 'status' => $status, 'message' => $err->getMessage() ]);
            }
        } else {
            return response()->json(['status' => $status]);
        }
    }

}
