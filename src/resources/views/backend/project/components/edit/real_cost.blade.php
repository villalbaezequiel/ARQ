@if((int) !$project->is_open_budget)
<div class="col-md-12 row">
    <div class="form-group">
      <div class="input-group-prepend">
        <span class="input-group-text col-md-2">
            Rubros
        </span>
        <div class="col-md-10">
            <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="items">
            @foreach($items as $val)
                <option value="{{ $val['id'] }}" data-number="{{ \App\Item::getNumberFromId($val['id']) }}">{{ $val['name'] }}</option>
            @endforeach
            </select>
        </div>

        @foreach($items as $val)
            <div class="col-md-5 hidden select-subitems" id="select-subitems-{{ $val['id'] }}">
                <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="sub_items" multiple>
                    @foreach($val['data'] as $budgetSubItem)
                        <option value="{{ $budgetSubItem->id }}" data-number="{{ \App\SubItem::getNumberFromId($budgetSubItem->id) }}">{{ $budgetSubItem->name }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <div class="col-md-4 text-center">
            <button class="btn btn-success" onclick="addGroupItemsRealCost(event); return false;">Agregar</button>
        </div>

        <div class="col-md-4 text-center">
            <a href="{{ url('/export/project_realcost/'.$project->id) }}" class="btn btn-success">Descargar</a>
        </div>

      </div>
    </div>
</div>
@endif

<div class="table-responsive">
    <div class="table-repsonsive">
      <table class="table table-bordered table-condensed" id="content-real-cost">
        
        <colgroup span="11"></colgroup>
        <thead>
            <tr class="b-c-gray">
                <th></th>
                <th class="font-weight-bold" style="width: 30%!important;" scope="col">Item Descripcion</th>
                <th class="font-weight-bold" style="width: 70%!important;" colspan="10" scope="colgroup">Seguimiento</th>
            </tr>
        </thead>
        <tbody>
            <tr class="b-c-gray">
                <th></th>
                <th></th>
                <th>CANT</th>
                <th>UNITARIO</th>
                <th>IMPUESTOS</th>
                <th>PRESUPUESTO</th>
                <th>PAGADO</th>
                <th>SALDO ITEM</th>
                <th>VARIACION ($)</th>
                <th>VARIACION (%)</th>
                <th></th>
            </tr>
            <tr class="b-c-gray">
                <th></th>
                <th colspan="4">TOTAL</th>
                <th><input type="text" name="global_costreal_budgetTotal"   class="form-control text-center" disabled></th>
                <th><input type="text" name="global_costreal_paid"          class="form-control text-center" disabled></th>
                <th><input type="text" name="global_costreal_balanceItem"   class="form-control text-center" disabled></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            @if((int) !$project->is_open_budget)
                @foreach($project_real_costs as $key => $collectionSubitem)
                    <tr class="b-c-gray costreal-item" id="costreal-item-{{ $key }}" data-id="{{ $key }}">
                        <th>{{ \App\Item::getNumberFromId($key) }}</th>
                        <th colspan="4" class="text-left pl-3">{{ \App\Item::getName($key) }}</th>
                        <th><input type="text" name="costreal_budget_total"   class="form-control text-center" disabled></th>
                        <th><input type="text" name="costreal_paid"           class="form-control text-center" disabled></th>
                        <th><input type="text" name="costreal_balance_item"   class="form-control text-center" disabled></th>
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
                        $balanceItem   = 0;
                    ?>

                    @foreach($collectionSubitem as $costReal)
                    <tr id="costreal-subitem-{{ $costReal->id }}" data-iditem="{{ $key }}" class="costreal-subitem">
                        
                        <input      type="hidden" name="real_cost[{{ $costReal->id_subitem }}][id]" value="{{ $costReal->id }}" />
                        <input      type="hidden" name="real_cost[{{ $costReal->id_subitem }}][id_item]" value="{{ $key }}" />

                        <td>{{ \App\SubItem::getNumberFromId($costReal->id_subitem) }}</td>
                        <td class="pl-3"><input class="form-control" value="{{ \App\SubItem::getName($costReal->id_subitem) }}" disabled /></td>
                        <td><input type="number"name="real_cost[{{ $costReal->id_subitem }}][quantity]" value="{{ $costReal->quantity }}"         id="quantity_realcost_{{ $costReal->id_subitem }}" data-idsubiteam="{{ $costReal->id_subitem }}" maxlength="11" class="form-control text-center calculate-subtotal-realcost" /></td>
                        <td><input type="text"  name="real_cost[{{ $costReal->id_subitem }}][unit_cost]"value="{{ $costReal->unit_cost }}"        id="unit_cost_realcost_{{ $costReal->id_subitem }}" data-idsubiteam="{{ $costReal->id_subitem }}" maxlength="14" class="form-control text-center validate-number-decimal calculate-subtotal-realcost" /></td>
                        <td><input type="text"  name="real_cost[{{ $costReal->id_subitem }}][tax]" value="{{ $costReal->tax }}"                   id="tax_realcost_{{ $costReal->id_subitem }}" data-idsubiteam="{{ $costReal->id_subitem }}" maxlength="6" class="form-control text-center validate-tax calculate-subtotal-realcost" /></td>
                        <td><input type="text"  name="real_cost[{{ $costReal->id_subitem }}][budget_total]" value="{{ $costReal->budget_total }}" id="budget_total_realcost_{{ $costReal->id_subitem }}" maxlength="14" class="form-control text-center validate-number-decimal" readonly /></td>
                        <td><input type="text"  name="real_cost[{{ $costReal->id_subitem }}][paid]" value="{{ $costReal->paid }}"        maxlength="14" class="form-control text-center validate-number-decimal" readonly /></td>
                        <td><input type="text"  name="real_cost[{{ $costReal->id_subitem }}][balance_item]" value="{{ $costReal->balance_item }}" id="balance_item_realcost_{{ $costReal->id_subitem }}" maxlength="14" class="form-control text-center validate-number-decimal" readonly /></td>
                        <td><input type="text"  name="real_cost[{{ $costReal->id_subitem }}][budget_variation_pesos]" value="{{ $costReal->budget_variation_pesos }}" id="budget_variation_pesos_realcost_{{ $costReal->id_subitem }}" maxlength="14" class="form-control text-center validate-number-decimal" readonly /></td>
                        <td><input type="text"  name="real_cost[{{ $costReal->id_subitem }}][budget_variation_tax]" value="{{ $costReal->budget_variation_tax }}"    maxlength="6" class="form-control text-center validate-tax" readonly /></td>
                        
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
                            $balanceItem   += $costReal->balance_item;
                        ?>
                    </tr>
                    @endforeach

                    <input type="text" class="hidden validate-number-decimal" name="costreal_budget_total_calc_{{ $key }}"   value="{{ $budgetTotal }}">
                    <input type="text" class="hidden validate-number-decimal" name="costreal_paid_calc_{{ $key }}"           value="{{ $paid }}">
                    <input type="text" class="hidden validate-number-decimal" name="costreal_balance_item_calc_{{ $key }}"   value="{{ $balanceItem }}">

                @endforeach
            @endif
        </tbody>
      </table>
    </div>
</div>

@if((int) $project->is_open_budget)
<div class="card">
    <div class="card-header">
        <p class="card-title text-center">Debes Aprobar el Presupuesto para generar la Plantilla de Seguimiento</p>
    </div>
</div>
@endif
