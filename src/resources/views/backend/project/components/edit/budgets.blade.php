<div class="col-md-12 row">
    <div class="form-group">
      <div class="input-group-prepend">
        <span class="input-group-text col-md-2">
            Rubros
        </span>
        <div class="col-md-10">
            <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="items">
            @foreach($items as $val)
                <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
            @endforeach
            </select>
        </div>

        @foreach($items as $val)
            <div class="col-md-5 hidden select-subitems" id="select-subitems-{{ $val['id'] }}">
                <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="sub_items" multiple>
                    @foreach($val['data'] as $budgetSubItem)
                        <option value="{{ $budgetSubItem->id_subitem }}">{{ $budgetSubItem->name }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <div class="col-md-4 text-center">
            <button class="btn btn-success" onclick="addGroupItems(event); return false;">Agregar</button>
        </div>

        <div class="col-md-4 text-center">
            <button class="btn btn-success">Aprobar</button>
        </div>

      </div>
    </div>
</div>

<div class="table-responsive">
    <div class="table-repsonsive">
      <table class="table table-bordered table-condensed" id="content-items">
        
        <colgroup span="8"></colgroup>
        <thead>
            <tr class="b-c-gray">
                <th class="w-50 font-weight-bold-600" scope="col">Item Descripcion</th>
                <th class="w-50 font-weight-bold-600" colspan="7" scope="colgroup">Cotizacion</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr class="b-c-gray">
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
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th><input type="number" name="global_subtotal"  value="0" class="form-control" readonly></th>
                <th></th>
                <th><input type="number" name="global_total"     value="0" class="form-control" readonly></th>
                <th><input type="number" name="global_incidence" value="100"class="form-control" readonly></th>
                <th></th>
            </tr>

            @foreach($project_budgets as $key => $collectionSubitem)
                <tr class="b-c-gray items_content" id="item-{{ $key }}" data-id="{{ $key }}">
                    <th colspan="4" class="text-left pl-3">{{ \App\Item::getName($key) }}</th>
                    <th><input type="number" name="item_subtotal" class="form-control validate-number-decimal" readonly></th>
                    <th></th>
                    <th><input type="number" name="item_total" class="form-control validate-number-decimal" readonly></th>
                    <th><input type="number" name="item_incidence" class="form-control validate-number-decimal" maxlength="6" readonly></th>
                    <th>
                        <button
                            class="btn btn-danger removeItem" 
                            data-iditeam="{{ $key }}" 
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
                <tr id="subitem-{{ $budgetSubItem->id_subitem }}" data-iditeam="{{ $key }}" class="sub_items_content">
                    
                    <input      type="hidden" name="budget[{{ $budgetSubItem->id_subitem }}][id]" value="{{ $budgetSubItem->id }}" />
                    <input      type="hidden" name="budget[{{ $budgetSubItem->id_subitem }}][id_item]" value="{{ $key }}" />

                    <td class="pl-3"><input class="form-control" value="{{ \App\SubItem::getName($budgetSubItem->id_subitem) }}" disabled /></td>
                    <td><input type="text"    name="budget[{{ $budgetSubItem->id_subitem }}][unit]"      value="{{ $budgetSubItem->unit }}" class="form-control" required /></td>
                    <td><input type="number"  name="budget[{{ $budgetSubItem->id_subitem }}][quantity]"  value="{{ $budgetSubItem->quantity }}" maxlength="11"  id="quantity_{{ $budgetSubItem->id_subitem }}" data-idsubiteam="{{ $budgetSubItem->id_subitem }}" class="form-control calculate-subtotal" required /></td>
                    <td><input type="number"  name="budget[{{ $budgetSubItem->id_subitem }}][unit_cost]" value="{{ $budgetSubItem->unit_cost }}" maxlength="10"  id="unit_cost_{{ $budgetSubItem->id_subitem }}" data-idsubiteam="{{ $budgetSubItem->id_subitem }}" class="form-control calculate-subtotal" required /></td>
                    <td><input type="number"  name="budget[{{ $budgetSubItem->id_subitem }}][subtotal]"  value="{{ $budgetSubItem->subtotal }}" maxlength="10"  id="subtotal_{{ $budgetSubItem->id_subitem }}" class="form-control" readonly required /></td>
                    <td><input type="text"    name="budget[{{ $budgetSubItem->id_subitem }}][tax]"       value="{{ $budgetSubItem->tax }}" maxlength="6"   class="form-control validate-number-decimal" required /></td>
                    <td><input type="number"  name="budget[{{ $budgetSubItem->id_subitem }}][total]"     value="{{ $budgetSubItem->total }}" maxlength="10"  id="total_{{ $budgetSubItem->id_subitem }}" class="form-control" readonly required /></td>
                    <td></td>
                    
                    <td class="text-center b-c-gray" style="vertical-align: inherit;">
                        <button
                        class="btn btn-danger" 
                        data-idsubitem="subitem-{{ $budgetSubItem->id_subitem }}" 
                        style="padding: 0px 5px;" 
                        onClick="jQuery('#subitem-{{ $budgetSubItem->id_subitem }}').remove(); return false;"
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