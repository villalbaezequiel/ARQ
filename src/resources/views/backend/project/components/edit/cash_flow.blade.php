<div class="row justify-content-center cash_flow_form">

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Fecha<span class="required-red">*</span>
            </span>
            <input class="form-control datepicker" type="text" name="cash_flow_date">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Tipo<span class="required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_type">
                @foreach(\App\ProjectCashFlow::getTypes() as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
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
                <input type="text" class="form-control validate-number-decimal-not-ps" name="cash_flow_amount" maxlength="12">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Impuesto %
            </span>
                <input type="text" class="form-control validate-number-decimal-not-ps" name="cash_flow_tax" maxlength="6">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Forma de Pago
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_payment_type">
                @foreach(\App\ProjectCashFlow::getPaymentTypes() as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Rubro
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" onchange="projectJS.getProvidersFromItem(this, 'cash_flow_id_provider')" name="cash_flow_id_item">
                <option value="">Seleccionar</option>
                @foreach($project_items as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Sub-Rubro
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_id_subitem">
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
                Detalle
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_detail">
                @foreach(\App\ProjectCashFlow::getDetails() as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Proveedor
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_id_provider">
                <option value="">Seleccionar</option>
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Aclaracion
            </span>
            <input type="text" class="form-control" name="cash_flow_clarification" maxlength="125">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Usuario<span class="required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_id_user">
                <option value="{{ Auth::user()->id }}">{{ Auth::user()->getFullNameAttribute() }}</option>
            </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Cliente
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_id_client">
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
        @if(!(int) $project->is_open_budget)
            <button type="button" class="btn btn-primary" onclick="addCashFlow(event); return false;">Agregar</button>
        @else
            <a href="javascript:void(0);" class="btn btn-default">Agregar</a>
        @endif
    </div>
</div>


<div class="table-responsive">
    <div class="table-repsonsive">
      <table class="table table-bordered table-condensed" id="content-providers">
        <thead>
            <tr class="b-c-gray">
                <th class="font-weight-bold-600">Fecha</th>
                <th class="font-weight-bold-600">Tipo</th>
                <th class="font-weight-bold-600">Saldo</th>
                <th class="font-weight-bold-600">Monto</th>
                <th class="font-weight-bold-600">Impuesto</th>
                <th class="font-weight-bold-600">Forma de pago</th>
                <th class="font-weight-bold-600">Rubro</th>
                <th class="font-weight-bold-600">Sub-Rubro</th>
                <th class="font-weight-bold-600">Detalle</th>
                <th class="font-weight-bold-600">Proveedor</th>
                <th class="font-weight-bold-600">Aclaración</th>
                <th class="font-weight-bold-600">Usuario</th>
                <th class="font-weight-bold-600">Cliente</th>
            </tr>
        </thead>
        <tbody>
        @if(!(int) $project->is_open_budget)
          @foreach($project_cash_flow as $cashFlow)
            <tr>
                <td>{{ $cashFlow->date }}</td>
                <td>{{ $cashFlow->type }}</td>
                <td>{{ $cashFlow->balance }}</td>
                <td>{{ $cashFlow->amount }}</td>
                <td>{{ $cashFlow->tax }}</td>
                <td>{{ $cashFlow->payment_type }}</td>
                <td>{{ $cashFlow->id_item }}</td>
                <td>{{ $cashFlow->id_subitem }}</td>
                <td>{{ $cashFlow->detail }}</td>
                <td>{{ $cashFlow->id_provider }}</td>
                <td>{{ $cashFlow->clarification }}</td>
                <td>{{ $cashFlow->id_user }}</td>
                <td>{{ $cashFlow->id_client }}</td>
            </tr>
          @endforeach
        @endif
        </tbody>
      </table>
    </div>
</div>