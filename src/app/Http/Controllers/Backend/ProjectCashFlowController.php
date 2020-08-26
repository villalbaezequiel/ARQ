<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Project;
use App\ProjectRealCost;
use App\ProjectCashFlow;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProjectCashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashFlow = ProjectCashFlow::where('is_approved', '0')->get();

        return view('backend.cash_flow.index', [
            'cash_flows' => $cashFlow
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status         = true;
        $edit           = false;
        $amount         = str_replace(array('.', ','), array('', '.'), $request->get('amount'));
        $userPermission = User::getPermission($request->get('id_user'));

        try {

            if((float) $request->get('id') > 0)
            {
                $projectCashFlow = ProjectCashFlow::find($request->get('id'));
                $amountOld       = $projectCashFlow->amount;
                $edit            = true;
            } else {
                $projectCashFlow = new ProjectCashFlow;
            }
            
            $projectCashFlow->id_project    = $request->get('id_project');
            $projectCashFlow->type          = $request->get('type');
            $projectCashFlow->amount        = Project::setParseFormatPrice($request->get('amount'));
            $projectCashFlow->tax           = Project::setParseFormatTax($request->get('tax'));
            $projectCashFlow->payment_type  = $request->get('payment_type');
            $projectCashFlow->id_item       = $request->get('id_item');
            $projectCashFlow->id_subitem    = $request->get('id_subitem');
            $projectCashFlow->detail        = $request->get('detail');
            $projectCashFlow->id_provider   = $request->get('id_provider');
            $projectCashFlow->clarification = $request->get('clarification');
            $projectCashFlow->id_user       = $request->get('id_user');
            $projectCashFlow->id_client     = $request->get('id_client');
            $projectCashFlow->is_approved   = ($userPermission == "admin" ? '1' : '0');
            $projectCashFlow->save();

            $projectCashFlow->setDate(Helpers::setDateFormatTimestamp($request->get('date')));

            if($userPermission == "admin")
            {
                if($request->get('type') == "egress" || $request->get('type') == "direct_payment")
                {
                    $realCost = ProjectRealCost::where('id_project', $request->get('id_project'))
                                                ->where('id_item', $request->get('id_item'))
                                                ->where('id_subitem', $request->get('id_subitem'))
                                                ->first();
                    if($realCost)
                    {
                        if($edit){
                            $realCost->paid         = ($realCost->paid - $amountOld) + Project::setParseFormatPrice($request->get('amount'));
                        } else {
                            $realCost->paid         = $realCost->paid + Project::setParseFormatPrice($request->get('amount'));
                        }
                        $realCost->balance_item = $realCost->budget_total - $realCost->paid;
                        $realCost->save();
                    }
                }
            }

        } catch (\Exception $err) {
            $status = false;
        }
        return response()->json(['status' => $status]);
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
        //
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
        $status = "Fujo de Caja Aprobado con exito!";
        try {
          if(Auth::user()->permissions == "admin")
            {
              $projectCashFlow                = ProjectCashFlow::find($id);
              $projectCashFlow->is_approved   = '1';
              $projectCashFlow->save();

              if($projectCashFlow->type == "egress" || $projectCashFlow->type == "direct_payment")
              {
                $realCost = ProjectRealCost::where('id_project', $projectCashFlow->id_project)
                                                ->where('id_item', $projectCashFlow->id_item)
                                                ->where('id_subitem', $projectCashFlow->id_subitem)
                                                ->first();
                if($realCost) {
                    $realCost->paid         = $realCost->paid + Project::setParseFormatPrice($projectCashFlow->amount);
                    $realCost->balance_item = $realCost->budget_total - $realCost->paid;
                    $realCost->save();
                }
              }
          }
        } catch (\Exception $err) {
            $status = "Hubo un error!";
        }
        return response()->json(['message' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        if(Auth::user()->permissions == "admin")
        {
            $projectCashFlow  = ProjectCashFlow::find($id);
            $projectCashFlow->delete();
        }
        return response()->json(['message' => 'Flujo de Caja eliminado con exito!']);
    }
}
