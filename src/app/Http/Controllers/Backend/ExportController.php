<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers;
use App\Item;
use App\SubItem;
use App\Provider;
use App\ProjectBudget;
use App\ProjectRealCost;
use App\ProjectCashFlow;
use App\Exports\ProjectProviderExport;
use App\Exports\ProviderExport;

class ExportController extends Controller
{

    public function projectBudgets($idProject)
    {
        try {
            return ProjectBudget::exportBudget($idProject);
        } catch (\Exception $err) {
            //throw $th;
        }
    }

    public function projectRealCost($idProject)
    {
        try {
            return ProjectRealCost::exportRealCost($idProject);
        } catch (\Exception $err) {
            //throw $th;
        }
    }
    
    public function projectHistoryProvider(Request $request)
    {
        try {
            $idProject  = $request->get('id_project');
            $idProvider = $request->get('id_provider');
            $timestamp  = date('mdhis');
            $providerHistory = ProjectCashFlow::select(
                                                'date',
                                                'amount',
                                                'tax',
                                                'payment_type',
                                                'id_item',
                                                'id_subitem',
                                                'detail',
                                                'clarification'
                                                )
                                                ->where('id_project', $idProject)
                                                ->where('id_provider', $idProvider)
                                                ->where('type', 'egress')
                                                ->orderBy('created_at', 'ASC')
                                                ->get();
            foreach($providerHistory as &$provider)
            {
                $provider->date     = Helpers::getDateParseFormat($provider->date);
                $provider->amount   = Helpers::getFormatSalary($provider->amount);
                $provider->tax      = "%".$provider->tax;
                $provider->payment_type = ProjectCashFlow::getPaymentType($provider->payment_type);
                $provider->id_item  = Item::getName($provider->id_item);
                $provider->id_subitem   = SubItem::getName($provider->id_subitem);
                $provider->detail   = ProjectCashFlow::getDetail($provider->detail);
            }

            $name = Provider::getName($idProvider);

            return (new ProjectProviderExport($providerHistory))->download("{$name}_{$idProject}_{$timestamp}.csv", \Maatwebsite\Excel\Excel::TSV);

        } catch (\Exception $err) {
            
        }
    }

    public function providers()
    {
        try {
            $timestamp  = date('mdhis');
            return (new ProviderExport())->download("proveedores_{$timestamp}.csv", \Maatwebsite\Excel\Excel::TSV);
        } catch (\Exception $err) {
            //throw $th;
        }
    }
}
