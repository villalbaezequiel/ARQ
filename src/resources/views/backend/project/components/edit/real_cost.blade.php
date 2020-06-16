<div class="table-responsive">
    <div class="table-repsonsive">
      <table class="table table-bordered table-condensed" id="content-real-cost">
        
        <colgroup span="11"></colgroup>
        <thead>
            <tr class="b-c-gray">
                <th class="w-50 font-weight-bold" scope="col">Item Descripcion</th>
                <th class="w-50 font-weight-bold" colspan="10" scope="colgroup">Seguimiento</th>
            </tr>
        </thead>
        <tbody>
            <tr class="b-c-gray">
                <th></th>
                <th>CANT</th>
                <th>UNITARIO</th>
                <th>IMPUESTOS</th>
                <th>PRESUPUESTO</th>
                <th>PAGADO</th>
                <th>SALDO PAGO</th>
                <th>SALDO ITEM</th>
                <th>VARIACION ($)</th>
                <th>VARIACION (%)</th>
                <th></th>
            </tr>
            <tr class="b-c-gray">
                <th colspan="4">TOTAL</th>
                <th><input type="number" name="global_costreal_budgetTotal"   class="form-control validate-number-decimal" readonly></th>
                <th><input type="number" name="global_costreal_paid"          class="form-control validate-number-decimal" readonly></th>
                <th><input type="number" name="global_costreal_balancePaid"   class="form-control validate-number-decimal" readonly></th>
                <th><input type="number" name="global_costreal_balanceItem"   class="form-control validate-number-decimal" readonly></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            @if((int) !$project->is_open_budget)
                @foreach($project_real_costs as $key => $collectionSubitem)
                    <tr class="b-c-gray costreal-item" id="costreal-item-{{ $key }}" data-id="{{ $key }}">
                        <th colspan="4" class="text-left pl-3">{{ \App\Item::getName($key) }}</th>
                        <th><input type="number" name="costreal_budget_total"   class="form-control validate-number-decimal" readonly></th>
                        <th><input type="number" name="costreal_paid"           class="form-control validate-number-decimal" readonly></th>
                        <th><input type="number" name="costreal_balance_paid"   class="form-control validate-number-decimal" readonly></th>
                        <th><input type="number" name="costreal_balance_item"   class="form-control validate-number-decimal" readonly></th>
                        <th></th>
                        <th></th>
                        <th>
                            <button
                                class="btn btn-danger removeCostRealItemAjax" 
                                data-idproject="{{ $project->id }}"
                                data-iditem="{{ $key }}"
                                style="padding: 0px 5px;"
                            >X</button>
                        </th>
                    </tr>

                    <?php 
                        $budgetTotal   = 0;
                        $paid          = 0;
                        $balancePaid   = 0;
                        $balanceItem   = 0;
                    ?>

                    @foreach($collectionSubitem as $costReal)
                    <tr id="costreal-subitem-{{ $costReal->id }}" data-iditem="{{ $key }}" class="costreal-subitem">
                        <td class="pl-3"><input class="form-control" value="{{ \App\SubItem::getName($costReal->id_subitem) }}" disabled /></td>
                        <td><input type="number"  value="{{ $costReal->quantity }}"    maxlength="11" class="form-control" /></td>
                        <td><input type="number"  value="{{ $costReal->unit_cost }}"   maxlength="11" class="form-control" /></td>
                        <td><input type="number"  value="{{ $costReal->tax }}"         maxlength="11" class="form-control" /></td>
                        <td><input type="number"  value="{{ $costReal->budget_total }}"maxlength="10" class="form-control" readonly /></td>
                        <td><input type="number"  value="{{ $costReal->paid }}"        maxlength="10" class="form-control" readonly /></td>
                        <td><input type="number"  value="{{ $costReal->balance_paid }}"maxlength="10"  class="form-control" readonly/></td>
                        <td><input type="number"  value="{{ $costReal->balance_item }}"maxlength="10" class="form-control" readonly /></td>
                        <td><input type="number"  value="{{ $costReal->budget_variation_pesos }}"  maxlength="10" class="form-control" readonly /></td>
                        <td><input type="number"  value="{{ $costReal->budget_variation_tax }}"    maxlength="10" class="form-control" readonly /></td>
                        
                        <td class="text-center b-c-gray" style="vertical-align: inherit;">
                            <button
                                class="btn btn-danger removeCostRealSubItemAjax" 
                                data-id="{{ $costReal->id }}"
                                style="padding: 0px 5px;" 
                            >X</button>
                        </td>

                        <?php 
                            $budgetTotal   += $costReal->budget_total;
                            $paid          += $costReal->paid;
                            $balancePaid   += $costReal->balance_paid;
                            $balanceItem   += $costReal->balance_item;
                        ?>
                    </tr>
                    @endforeach

                    <input type="hidden" name="costreal_budget_total_calc_{{ $key }}"   value="{{ $budgetTotal }}">
                    <input type="hidden" name="costreal_paid_calc_{{ $key }}"           value="{{ $paid }}">
                    <input type="hidden" name="costreal_balance_paid_calc_{{ $key }}"   value="{{ $balancePaid }}">
                    <input type="hidden" name="costreal_balance_item_calc_{{ $key }}"   value="{{ $balanceItem }}">

                @endforeach
            @endif
        </tbody>
      </table>
    </div>
</div>

@if((int) $project->is_open_budget)
<div class="card">
    <div class="card-header">
        <p class="card-title text-center">Debes Aprobar el Presupuesto para generar la Plantilla de Costo Real</p>
    </div>
</div>
@endif
