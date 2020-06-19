@if((int) !$project->is_open_budget)
<div class="col-md-12 row">
    <div class="form-group">
      <div class="input-group-prepend">
        <span class="input-group-text row">
            Rubros / Proveedores
        </span>
        <div class="col-md-10">
            <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="sub_items_realcost">
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

        <!-- <div class="col-md-6" id="select-providers">
            <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="providers">
            </select>
        </div> -->

        <div class="col-md-5 text-center">
            <button class="btn btn-success" onclick="addProviders(event); return false;">Buscar</button>
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
                <?php $count = 0; ?>
                @foreach($project_providers as $itemProvider)
                <tr>
                    <td class="font-weight-bold-600">#{{ $count }}</td>
                    <td>{{ \App\ProjectRealCost::getNameSubItem($itemProvider->id_project_real_cost) }}</td>
                    <td>{{ \App\Provider::getName($itemProvider->id_provider) }}</td>        
                    <td class="text-center b-c-gray" style="vertical-align: inherit;">
                        <button
                            class="btn btn-danger removeProjectProvider" 
                            data-id="{{ $itemProvider->id }}"
                            style="padding: 0px 5px;"
                        >X</button>
                    </td>
                </tr>
                <?php $count ++; ?>
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