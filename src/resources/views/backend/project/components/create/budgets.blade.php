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
                    @foreach($val['data'] as $subitem)
                        <option value="{{ $subitem->id }}">{{ $subitem->name }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <div class="col-md-4 text-center">
            <button class="btn btn-success" onclick="addGroupItems(event); return false;">Agregar</button>
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
                <th><input type="number" name="global_incidence" value="0"class="form-control" readonly></th>
                <th></th>
            </tr>
        </tbody>
      </table>
    </div>
</div>