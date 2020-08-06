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
        <tbody id="content-data-budget">
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

<script type="text/javascript">
    let projectBudgets = {!! json_encode($project_budgets,JSON_FORCE_OBJECT) !!};
</script>