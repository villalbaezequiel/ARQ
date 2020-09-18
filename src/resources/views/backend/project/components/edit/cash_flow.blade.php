<div class="row justify-content-center cash_flow_form">

    <input type="hidden" name="cash_flow_id" value="0">

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Fecha<span class="default-required required-red">*</span>
            </span>
            <input class="form-control datepicker" type="text" name="cash_flow_date">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Tipo<span class="default-required required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" onchange="projectJS.toggleFieldRequired(this);" name="cash_flow_type">
                <option value="">Seleccionar</option>
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
                Monto<span class="default-required required-red" style="margin-right: 4px;">*</span>
            </span>
                <input type="text" class="form-control validate-number-decimal" name="cash_flow_amount" pattern="^$d{1,3}(,d{3})*(.d+)?$" maxlength="14">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Impuesto<span class="hidden required-red" style="margin-right: 4px;">*</span> %
            </span>
                <input type="number" class="form-control" pattern="/^-?\d+\.?\d*$/" name="cash_flow_tax" onkeypress="if(this.value.length == 3) return false;" maxlength="5" value="0">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Forma de Pago<span class="default-required required-red">*</span>
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
                Rubro<span class="hidden egress-required direct_payment-required required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" onchange="projectJS.getProvidersFromItem(this, 'cash_flow_id_provider', 'cash_flow_id_subitem')" name="cash_flow_id_item">
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
                Sub-Rubro<span class="hidden egress-required direct_payment-required required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_id_subitem">
                <option value="">Seleccionar</option>
            </select>

            <div class="hidden cash_flow_id_subitem_collection">
              @foreach($project_real_costs as $key => $item)
                <optgroup data-id="{{ $key }}" label="{{ $item['item_name'] }}">
                    @foreach($item as $subitem)
                        @if(isset($subitem->id_subitem))
                        <option value="{{ $subitem->id_subitem }}" data-id="{{ $subitem->id }}">{{ $subitem->name_subitem }}</option>
                        @endif
                    @endforeach
                </optgroup>
              @endforeach
            </div>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Detalle<span class="default-required required-red">*</span>
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
                Proveedor<span class="hidden egress-required required-red">*</span>
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
                Aclaracion<span class="hidden required-red">*</span>
            </span>
            <input type="text" class="form-control" name="cash_flow_clarification" maxlength="125">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Usuario<span class="default-required required-red">*</span>
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_id_user">
                <option value="{{ Auth::user()->id }}">{{ Auth::user()->getFullNameAttribute() }}</option>
            </select>
            </div>
        </div>
    </div>
    
    <div class="col-md-6"></div>

    <div class="hidden col-md-6">
        <div class="form-group">
            <div class="input-group-prepend">
            <span class="input-group-text">
                Cliente
            </span>
            <select class="selectpicker" data-live-search="true" data-style="select-with-transition" name="cash_flow_id_client">
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
            <button type="button" class="btn btn-primary label-action-cashflow" onclick="addCashFlow(event); return false;">Agregar</button>
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
                <th class="font-weight-bold-600">Monto</th>
                <th class="font-weight-bold-600">Saldo</th>
                <th class="font-weight-bold-600">Impuesto</th>
                <th class="font-weight-bold-600">Forma de pago</th>
                <th class="font-weight-bold-600">Rubro</th>
                <th class="font-weight-bold-600">Sub-Rubro</th>
                <th class="font-weight-bold-600">Detalle</th>
                <th class="font-weight-bold-600">Proveedor</th>
                <th class="font-weight-bold-600">Aclaración</th>
                <th class="font-weight-bold-600">Usuario</th>
                <th class="font-weight-bold-600">Accion</th>
                <!-- <th class="font-weight-bold-600">Cliente</th> -->
            </tr>
        </thead>
        <tbody>
        @if(!(int) $project->is_open_budget)
          @foreach($project_cash_flow as $cashFlow)
            <tr <?php if($cashFlow->is_approved == "0"){ echo "style='background-color: rgba(0,0,0,.05);'"; }?> >
                <td>{{ $cashFlow->date }}</td>
                <td>{{ $cashFlow->name_type }}</td>
                <td>{{ $cashFlow->amount }}</td>
                <td>{{ $cashFlow->balance }}</td>
                <td>{{ $cashFlow->tax }}</td>
                <td>{{ $cashFlow->name_payment_type }}</td>
                <td>{{ $cashFlow->name_item }}</td>
                <td>{{ $cashFlow->name_subitem }}</td>
                <td>{{ $cashFlow->name_detail }}</td>
                <td>{{ $cashFlow->name_provider }}</td>
                <td>{{ $cashFlow->clarification }}</td>
                <td>{{ $cashFlow->name_user }}</td>
                <td>
                    <button onclick="editCashFlow(event, this); return false;" data-idcashflow="{{ $cashFlow->id }}" class="btn btn-link btn-warning btn-just-icon edit" title="Editar">
                        <i class="material-icons">dvr</i>
                    </button>
                </td>
                
                <input type="hidden" name="cashflow_id_{{ $cashFlow->id }}"         value="{{ $cashFlow->id }}" />
                <input type="hidden" name="cashflow_date_{{ $cashFlow->id }}"       value="{{ $cashFlow->date }}" />
                <input type="hidden" name="cashflow_type_{{ $cashFlow->id }}"       value="{{ $cashFlow->type }}" />
                <input type="hidden" name="cashflow_amount_{{ $cashFlow->id }}"     value="{{ $cashFlow->amount }}" />
                <input type="hidden" name="cashflow_tax_{{ $cashFlow->id }}"        value="{{ $cashFlow->tax }}" />
                <input type="hidden" name="cashflow_payment_type_{{ $cashFlow->id }}"value="{{ $cashFlow->payment_type }}" />
                <input type="hidden" name="cashflow_id_item_{{ $cashFlow->id }}"    value="{{ $cashFlow->id_item }}" />
                <input type="hidden" name="cashflow_id_subitem_{{ $cashFlow->id }}" value="{{ $cashFlow->id_subitem }}" />
                <input type="hidden" name="cashflow_detail_{{ $cashFlow->id }}"     value="{{ $cashFlow->detail }}" />
                <input type="hidden" name="cashflow_id_provider_{{ $cashFlow->id }}"value="{{ $cashFlow->id_provider }}" />
                <input type="hidden" name="cashflow_clarification_{{ $cashFlow->id }}"value="{{ $cashFlow->clarification }}" />

                <!-- <td>$cashFlow->id_client</td> -->
            </tr>
          @endforeach
        @endif
        </tbody>
      </table>
      <input type="hidden" name="balance-cash-flow" value="{{ $project_cash_flow_balance_total }}">
    </div>
</div>