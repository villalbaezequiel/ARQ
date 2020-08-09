@if((int) !$project->is_open_budget)
<div class="col-md-12 row">
    <div class="form-group">
      <div class="input-group-prepend">
        <span class="input-group-text col-md-3">
            Rubros
        </span>
        <div class="col-md-8">
            <select class="form-control selectpicker select-items-realcost" data-style="btn btn-link" data-live-search="true" name="items">
            @foreach($items as $val)
                <option value="{{ $val['id'] }}" data-number="{{ \App\Item::getNumberFromId($val['id']) }}">{{ $val['name'] }}</option>
            @endforeach
            </select>
        </div>

        @foreach($items as $val)
            <div class="col-md-8 hidden select-subitems select-subitems-realcost" id="select-subitems-{{ $val['id'] }}">
                <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="sub_items">
                    @foreach($val['data'] as $budgetSubItem)
                        <option value="{{ $budgetSubItem->id }}" data-number="{{ \App\SubItem::getNumberFromId($budgetSubItem->id) }}">{{ $budgetSubItem->name }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <div class="col-md-4 text-center">
            <button class="btn btn-success" onclick="addGroupItemsRealCost(event); return false;">Agregar</button>
        </div>

      </div>
    </div>
</div>
<div class="col-md-12 row">
    <a href="{{ url('/export/project_realcost/'.$project->id) }}" class="btn btn-success" style="margin: 8px auto;">Descargar</a>
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
        <tbody id="content-data-realcost">
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
                <th><input type="text" name="global_costreal_budgetTotal"   class="form-control text-center" style="font-weight: 700;" disabled></th>
                <th><input type="text" name="global_costreal_paid"          class="form-control text-center" style="font-weight: 700;" disabled></th>
                <th><input type="text" name="global_costreal_balanceItem"   class="form-control text-center" style="font-weight: 700;" disabled></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
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
@else
<script type="text/javascript">
    let projectRealCosts = {!! json_encode($project_real_costs,JSON_FORCE_OBJECT) !!};
</script>
@endif
