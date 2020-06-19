<div class="row justify-content-center">

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Fecha<span class="required-red">*</span>
            </span>
            <input class="form-control datepicker" type="text" name="date">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
            Forma de Pago<span class="required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="type_pay">
                <option value="">Ingreso</option>
                <option value="">Egreso</option>
                <option value="">Pago directo</option>
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Monto<span class="required-red" style="margin-right: 4px;">*</span> $
            </span>
                <input type="text" class="form-control validate-number-decimal-not-ps" name="amount" maxlength="10">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Impuesto<span class="required-red" style="margin-right: 4px;">*</span> %
            </span>
                <input type="text" class="form-control validate-number-decimal-not-ps" name="tax" maxlength="6">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
            Forma de Pago<span class="required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="way_to_pay">
                <option value="">Seleccionar</option>
                <option value="">Efectivo</option>
                <option value="">Transferencia</option>
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
            Rubro<span class="required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="item">
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
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
            Sub-Rubro<span class="required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="sub_item">
                <option value="">Seleccionar</option>
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Detalle<span class="required-red">*</span>
            </span>
            <input type="text" class="form-control" name="detail" maxlength="125">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Proveedor<span class="required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="id_provider">
                <option value="">Seleccionar</option>
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Aclaracion<span class="required-red">*</span>
            </span>
            <input type="text" class="form-control" name="clarification" maxlength="125">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Usuario<span class="required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="id_user">
                <option value="{{ Auth::user()->id }}">{{ Auth::user()->getFullNameAttribute() }}</option>
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Cliente<span class="required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="">
                <option value="">Seleccionar</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">
                        {{ $client->last_name .' '. $client->name }}
                    </option>
                @endforeach
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-12 text-center" style="padding: 20px;">
        <button id="" type="button" class="btn btn-primary">Agregar</button>
    </div>
</div>


<div class="table-responsive">
    <div class="table-repsonsive">
      <table class="table table-bordered table-condensed" id="content-providers">
        <thead>
            <tr class="b-c-gray">
                <th class="font-weight-bold-600">Fecha</th>
                <th class="font-weight-bold-600">Ingreso</th>
                <th class="font-weight-bold-600">Egreso</th>
                <th class="font-weight-bold-600">Impuesto</th>
                <th class="font-weight-bold-600">Saldo</th>
                <th class="font-weight-bold-600">Forma de pago</th>
                <th class="font-weight-bold-600">Rubro</th>
                <th class="font-weight-bold-600">Sub-Rubro</th>
                <th class="font-weight-bold-600">Detalle</th>
                <th class="font-weight-bold-600">Proveedor</th>
                <th class="font-weight-bold-600">Aclaración</th>
                <th class="font-weight-bold-600">Usuario</th>
                <th class="font-weight-bold-600">Cliente</th>
                <th class="font-weight-bold-600">Pago directo</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
</div>