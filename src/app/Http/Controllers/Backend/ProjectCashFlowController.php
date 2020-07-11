<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Project;
use App\ProjectCashFlow;

class ProjectCashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $status = true;

        $amount = str_replace(array('.', ','), array('', '.'), $request->get('amount'));

        try {
            $projectCashFlow = new ProjectCashFlow;
            $projectCashFlow->id_project    = $request->get('id_project');
            $projectCashFlow->date          = Helpers::setDateFormatTimestamp($request->get('date'));
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
            $projectCashFlow->save();

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
