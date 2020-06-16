<div class="col-md-12 row">
    <div class="form-group">
      <div class="input-group-prepend">
        <span class="input-group-text col-md-5 row">
            Rubros / Proveedores
        </span>
        <div class="col-md-6">
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

        <div class="col-md-6" id="select-providers">
            <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="providers">
            </select>
        </div>

        <div class="col-md-5 text-center">
            <button class="btn btn-success" onclick="addProviders(event); return false;">Agregar</button>
        </div>

      </div>
    </div>
</div>

<div class="table-responsive">
    <div class="table-repsonsive">
      <table class="table table-bordered table-condensed" id="content-providers">
        
        <thead>
            <tr class="b-c-gray">
                <th class="font-weight-bold-600">Sub-Rubro</th>
                <th class="font-weight-bold-600">Proveedor</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($project_providers as $itemProvider)
            <tr>
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
            @endforeach
        </tbody>
      </table>
    </div>
</div>