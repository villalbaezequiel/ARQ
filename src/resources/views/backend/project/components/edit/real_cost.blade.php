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
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            @if((int) !$project->is_open_budget)
                @foreach($project_budgets as $key => $collectionSubitem)
                    <tr class="b-c-gray items_content" id="item-{{ $key }}" data-id="{{ $key }}">
                        <th colspan="4" class="text-left pl-3">{{ \App\Item::getName($key) }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                            <a href="javascript:void(0);" class="btn btn-danger" style="padding: 0px 5px;">X</a>
                        </th>
                    </tr>

                    @foreach($collectionSubitem as $budgetSubItem)
                    <tr> 
                        <td class="pl-3"><input class="form-control" value="{{ \App\SubItem::getName($budgetSubItem->id_subitem) }}" disabled /></td>
                        <td><input type="number"  value="{{ $budgetSubItem->quantity }}" maxlength="11" class="form-control" /></td>
                        <td><input type="number"  value="{{ $budgetSubItem->unit_cost }}" maxlength="11" class="form-control" /></td>
                        <td><input type="number"  value="{{ $budgetSubItem->tax }}" maxlength="11" class="form-control" /></td>
                        <td><input type="number"  value="{{ $budgetSubItem->total }}" maxlength="10" class="form-control" readonly /></td>
                        <td><input type="number"  maxlength="10" class="form-control" readonly /></td>
                        <td><input type="number"  maxlength="6"  class="form-control validate-number-decimal" readonly/></td>
                        <td><input type="number"  maxlength="10" class="form-control" readonly /></td>
                        <td><input type="number"  maxlength="10" class="form-control" readonly /></td>
                        <td><input type="number"  maxlength="10" class="form-control" readonly /></td>
                        
                        <td class="text-center b-c-gray" style="vertical-align: inherit;">
                            <a href="javascript:void(0);" class="btn btn-danger" style="padding: 0px 5px;">X</a>
                        </td>
                    </tr>
                    @endforeach
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
