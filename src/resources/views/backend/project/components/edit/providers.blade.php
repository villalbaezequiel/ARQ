@if((int) !$project->is_open_budget)
<div class="col-md-12 row">
    <div class="form-group">
      <div class="input-group-prepend">
        <span class="input-group-text row">
            Rubros / Proveedores
        </span>
        <div class="col-md-10">
            <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true">
                <option value="">Seleccionar</option>
                @foreach($project_real_costs as $key => $item)
                    <optgroup label="{{ \App\Item::getName($key) }}">
                        @foreach($item as $subitem)
                            <option value="{{ $subitem->id_subitem }}" data-id="{{ $subitem->id }}">{{ \App\SubItem::getName($subitem->id_subitem) }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="col-md-5 text-center">
            <button class="btn btn-success" onclick="searchProviders(event); return false;">Buscar</button>
        </div>

      </div>
    </div>
</div>
@endif

<div class="table-responsive">
    <div class="table-repsonsive">
      <table class="table table-bordered table-condensed" id="content-providers">
        
        <thead>
            <tr class="b-c-gray">
                <th class="font-weight-bold-600">ID</th>
                <th class="font-weight-bold-600">Nombre / Razon Social</th>
                <th class="font-weight-bold-600">Monto</th>
                <th class="font-weight-bold-600">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if((int) !$project->is_open_budget)

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