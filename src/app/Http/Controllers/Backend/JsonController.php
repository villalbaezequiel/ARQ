<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Project;
use App\ProjectBudget;

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

                $project = Project::find($request->get('id_project'));
                $project->is_open_budget = '0';
                $project->save();

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
