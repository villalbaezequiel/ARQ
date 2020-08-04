<div class="col-md-12 row">
    <div class="form-group is_open_budget">
      <div class="input-group-prepend">

        @if((int) $project->is_open_budget)
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
        @endif

        @if((int) $project->is_open_budget)
            <div class="col-md-4 text-center">
                <button class="btn btn-success" onclick="addGroupItems(event); return false;">Agregar</button>
            </div>
        @else
            <div class="col-md-6 text-center">
                <a href="{{ url('/export/project_budgets/'.$project->id) }}" class="btn btn-success">Descargar</a>
            </div>
        @endif

        @if((int) $project->is_open_budget)
            <div class="col-md-4 text-center">
                <a href="javascript:void(0);" class="btn btn-success open-budget" data-idproject="{{ $project->id }}">Aprobar</a>
            </div>
        @else
            <div class="col-md-6 text-center">
                <a href="javascript:void(0);" class="btn btn-default">Aprobado</a>
            </div>
        @endif
        <input type="hidden" name="open_budget" value="{{ (int) $project->is_open_budget }}">

      </div>
    </div>
</div>

<div class="table-responsive">
    <div class="table-repsonsive">
      <table class="table table-bordered table-condensed" id="content-items">
        
        <colgroup span="8"></colgroup>
        <thead>
            <tr class="b-c-gray">
                <th></th>
                <th class="font-weight-bold-600" style="width: 30%!important;" scope="col">Item Descripcion</th>
                <th class="font-weight-bold-600" style="width: 70%!important;" colspan="7" scope="colgroup">Cotizacion</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr class="b-c-gray">
                <th></th>
                <th></th>
                <th>U</th>
                <th>CAN</th>
                <th>UNITARIO</th>
                <th>SUBTOTAL</th>
                <th>IMPUESTOS</th>
                <th>TOTAL</th>
                <th>INCIDENCIA</th>
                <th></th>
            </tr>
            <tr class="b-c-gray">
                <th></th>
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th><input type="text" name="global_subtotal" class="form-control text-center" disabled></th>
                <th></th>
                <th><input type="text" name="global_total"    class="form-control text-center" disabled></th>
                <th><input type="text" name="global_incidence"class="form-control text-center validate-tax" value="100" disabled></th>
                <th></th>
            </tr>

            @foreach($project_budgets as $key => $collectionSubitem)
                <tr class="b-c-gray items_content" id="item-{{ $key }}" data-id="{{ $key }}">
                    <th>{{ \App\Item::getNumberFromId($key) }}</th>
                    <th colspan="4" class="text-left pl-3">{{ \App\Item::getName($key) }}</th>
                    <th><input type="text"  name="item_subtotal" class="form-control text-center validate-number-decimal" disabled></th>
                    <th></th>
                    <th><input type="text"  name="item_total" class="form-control text-center validate-number-decimal" disabled></th>
                    <th><input type="text"name="item_incidence" class="form-control text-center validate-tax" maxlength="6" disabled></th>
                    <th>
                        <button
                            class="btn btn-danger removeItem removeItemAjax" 
                            data-idproject="{{ $project->id }}"
                            data-iditem="{{ $key }}"
                            style="padding: 0px 5px;"
                        >X</button>
                    </th>
                </tr>

                <?php 
                    $itemSubtotal   = 0;
                    $itemTotal      = 0;
                    $itemIncidene   = 0;
                ?>

                @foreach($collectionSubitem as $budgetSubItem)
                <tr id="subitem-{{ $budgetSubItem->id_subitem }}" data-id="{{ $budgetSubItem->id_subitem }}" data-iditem="{{ $key }}" class="sub_items_content">
                    
                    <input      type="hidden" name="budget[{{ $budgetSubItem->id_subitem }}][id]" value="{{ $budgetSubItem->id }}" />
                    <input      type="hidden" name="budget[{{ $budgetSubItem->id_subitem }}][id_item]" value="{{ $key }}" />

                    <td>{{ \App\SubItem::getNumberFromId($budgetSubItem->id_subitem) }}</td>
                    <td class="pl-3"><input class="form-control" value="{{ \App\SubItem::getName($budgetSubItem->id_subitem) }}" disabled /></td>
                    <td><input type="text"  name="budget[{{ $budgetSubItem->id_subitem }}][unit]"      value="{{ $budgetSubItem->unit }}" class="form-control text-center" required /></td>
                    <td><input type="number"name="budget[{{ $budgetSubItem->id_subitem }}][quantity]"  value="{{ $budgetSubItem->quantity }}" maxlength="11"  id="quantity_{{ $budgetSubItem->id_subitem }}" data-idsubiteam="{{ $budgetSubItem->id_subitem }}" class="form-control text-center calculate-subtotal" required /></td>
                    <td><input type="text"  name="budget[{{ $budgetSubItem->id_subitem }}][unit_cost]" value="{{ $budgetSubItem->unit_cost }}" maxlength="14" id="unit_cost_{{ $budgetSubItem->id_subitem }}" data-idsubiteam="{{ $budgetSubItem->id_subitem }}" class="form-control text-center calculate-subtotal validate-number-decimal reset-value" required /></td>
                    <td><input type="text"  name="budget[{{ $budgetSubItem->id_subitem }}][subtotal]"  value="{{ $budgetSubItem->subtotal }}" maxlength="14"  id="subtotal_{{ $budgetSubItem->id_subitem }}" class="form-control text-center validate-number-decimal" readonly required /></td>
                    <td><input type="text"  name="budget[{{ $budgetSubItem->id_subitem }}][tax]"       value="{{ $budgetSubItem->tax }}" maxlength="6"        id="tax_{{ $budgetSubItem->id_subitem }}" data-idsubiteam="{{ $budgetSubItem->id_subitem }}" class="form-control text-center validate-tax calculate-subtotal reset-value" /></td>
                    <td><input type="text"  name="budget[{{ $budgetSubItem->id_subitem }}][total]"     value="{{ $budgetSubItem->total }}" maxlength="14"     id="total_{{ $budgetSubItem->id_subitem }}" class="form-control text-center validate-number-decimal" readonly required /></td>
                    <td><input type="text"  name="budget[{{ $budgetSubItem->id_subitem }}][incidence]" value="" maxlength="6"        id="incidence_{{ $budgetSubItem->id_subitem }}" data-idsubiteam="{{ $budgetSubItem->id_subitem }}" class="form-control text-center validate-tax" readonly /></td>
                    
                    <td class="text-center b-c-gray" style="vertical-align: inherit;">
                        <button
                        class="btn btn-danger removeSubItemAjax" 
                        data-idsubitem="subitem-{{ $budgetSubItem->id_subitem }}" 
                        data-id="{{ $budgetSubItem->id }}"
                        style="padding: 0px 5px;" 
                        >X</button>
                    </td>

                    <?php 
                        $itemSubtotal   += $budgetSubItem->subtotal;
                        $itemTotal      += $budgetSubItem->total;
                    ?>
                </tr>
                @endforeach
                
                <input type="hidden" name="item_subtotal_calc_{{ $key }}" value="{{ $itemSubtotal }}">
                <input type="hidden" name="item_total_calc_{{ $key }}" value="{{ $itemTotal }}">
               
            @endforeach
        </tbody>
      </table>
    </div>
</div>

@if(!(int) $project->is_open_budget)
<style>
    .is_open_budget {
        margin: 0 auto!important;
    }
</style>
@endif