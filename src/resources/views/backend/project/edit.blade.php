@extends('layouts.admin')

@section('title', "Proyectos / Editar")

@section('content')

<div class="container-fluid">
  <div class="col-md-12 col-12 mr-auto ml-auto">

    @if (session('message'))
      <div class="toolbar">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          {{ session('message') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    @endif

    <div class="wizard-container">
      <div class="card card-wizard active" data-color="purple" id="wizardProfile">

        {!! Form::open(['route' => ['projects.update', $project->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'onKeyPress' => 'return disableEnterKey(event)', 'id' => 'logic_project', 'data-id' => $project->id, 'enctype' => 'multipart/form-data']) !!}
          <div class="card-header text-center">
            <h3 class="card-title">
              Editar Proyecto: <strong>{{ $project->name }}</strong>
            </h3>
          </div>
          <div class="wizard-navigation">
            <ul class="nav nav-pills">
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link disabled-click active" href="#module_one" data-toggle="tab" role="tab">
                  Información
                </a>
              </li>

              @if(Auth::user()->permissions == "admin")
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link disabled-click" href="#module_two" data-toggle="tab" role="tab">
                  Presupuesto
                </a>
              </li>
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link disabled-click" href="#module_three" data-toggle="tab" role="tab">
                  Seguimiento
                </a>
              </li>
              @endif

              @if(Auth::user()->permissions != "junior")
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link disabled-click cash_flow_link" href="#module_five" data-toggle="tab" role="tab">
                  Flujo de caja
                </a>
              </li>
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link disabled-click" href="#module_six" data-toggle="tab" role="tab">
                  Proveedores
                </a>
              </li>
              @endif

              <li class="nav-item" style="width: 50%;">
                <a class="nav-link disabled-click" href="#module_four" data-toggle="tab" role="tab">
                  Documentos
                </a>
              </li>
            </ul>

            <div class="moving-tab" style="width: 50%; transform: translate3d(-8px, 0px, 0px); transition: transform 0s ease 0s;">
              Informacion
            </div>
          </div>
          <div class="card-body">

            <div class="row pr-3 pl-3">
                <div class="mr-auto">
                    <input type="button" class="btn btn-previous btn-fill btn-default btn-wd save-continue disabled" name="previous" value="Anterior">
                </div>
                <div class="ml-auto">
                    <input type="button" class="btn btn-next btn-fill btn-primary btn-wd save-continue" name="next" value="Guardar y Siguiente">
                    <input type="submit" class="btn btn-finish btn-fill btn-primary btn-wd show-content-observation" name="finish" value="Guardar" />
                </div>
            </div>

            <div class="tab-content">
              <div class="tab-pane active" id="module_one">
                <h5 class="info-text"> Información General </h5>
                <div class="row justify-content-center">

                  <div class="col-md-5">
                    <div class="card ">
                      <div class="card-header card-header-rose card-header-text">
                        <div class="card-icon card-icon-project">
                          <i class="material-icons">library_books</i>
                        </div>
                        <h4 class="card-title">Fecha de Inicio</h4>
                      </div>
                      <div class="card-body ">
                        <div class="form-group is-filled">
                          <?php $start_date = explode('-', \App\Helpers::getDate($project->start_date)); ?>
                          <input type="text" class="form-control datepicker" value="{{ $start_date[2].'/'.$start_date[1].'/'.$start_date[0] }}" name="start_date" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="card ">
                      <div class="card-header card-header-rose card-header-text">
                        <div class="card-icon card-icon-project">
                          <i class="material-icons">library_books</i>
                        </div>
                        <h4 class="card-title">Fecha de Finalización</h4>
                      </div>
                      <div class="card-body ">
                        <div class="form-group is-filled">
                          <?php $finish_date = explode('-', \App\Helpers::getDate($project->finish_date)); ?>
                          <input type="text" class="form-control datepicker" value="{{ $finish_date[2].'/'.$finish_date[1].'/'.$finish_date[0] }}" name="finish_date" required>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Cliente<span class="required-red">*</span>
                        </span>
                        <select class="form-control selectpicker" data-style="btn btn-link" name="id_client[]" multiple required>
                          <option value="99999">Seleccionar</option>
                          @foreach($clients as $client)
                            <option value="{{ $client->id }}"
                              <?php foreach(json_decode($project->id_client) as $key => $val) {
                                if($val == $client->id){ echo "selected"; }
                              }?>
                            >
                              {{ $client->last_name .' '. $client->name }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Nombre<span class="required-red">*</span>
                        </span>
                        <input type="text" class="form-control" name="name" value="{{ $project->name }}" maxlength="125" required>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Descripción
                        </span>
                        <input type="text" class="form-control" name="description" value="{{ $project->description }}" maxlength="125">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Ubicacion
                        </span>
                        <div class="col-md-4">
                          <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="id_country">
                            @foreach(\App\Helpers::getCountries() as $key => $val)
                              <option value="{{ $key }}" <?php if($project->id_country == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-md-4">
                          <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="id_province">
                            @foreach(\App\Helpers::getProvinces() as $key => $val)
                              <option value="{{ $key }}" <?php if($project->id_province == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-2">
                          <input class="form-control" type="text" name="location" value="{{ $project->location }}" placeholder="Localidad">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Domicilio
                        </span>
                        <div class="col-md-4">
                          <input class="form-control" type="text" name="address" value="{{ $project->address }}">
                        </div>

                        <div class="col-md-2">
                          <input class="form-control input-type-number" type="text" name="address_number" value="{{ $project->address_number }}" placeholder="Número">
                        </div>
                        <div class="col-md-2">
                          <input class="form-control" type="text" name="piso_depto" value="{{ $project->piso_depto }}" placeholder="Piso/Depto">
                        </div>
                        <div class="col-md-2">
                          <input class="form-control input-type-number" type="text" name="cp" value="{{ $project->cp }}" placeholder="CP">
                        </div>
                      </div>
                    </div>
                  </div> 

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Proyecto Activo
                        </span>
                        <select class="form-control selectpicker" data-style="btn btn-link" name="is_active">
                          <option value="1" <?php if($project->is_active == "1"){ echo "selected='selected'"; } ?>>Si</option>
                          <option value="0" <?php if($project->is_active == "0"){ echo "selected='selected'"; } ?>>No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                          
                </div>
              </div>

              @if(Auth::user()->permissions == "admin")
              <div class="tab-pane" id="module_two">
                <h5 class="info-text"> Presupuesto </h5>
                @include('backend.project.components.edit.budgets')
              </div>

              <div class="tab-pane" id="module_three">
                <h5 class="info-text"> Seguimiento </h5>
                @include('backend.project.components.edit.real_cost')
              </div>
              @endif

              @if(Auth::user()->permissions != "junior")
              <div class="tab-pane" id="module_five">
                <h5 class="info-text"> Flujo de Caja </h5>
                @include('backend.project.components.edit.cash_flow')
              </div>

              <div class="tab-pane" id="module_six">
                <h5 class="info-text"> Proveedores </h5>
                @include('backend.project.components.edit.providers')
              </div>
              @endif

              <div class="tab-pane" id="module_four">
                <h5 class="info-text"> Documentos </h5>
                @include('backend.project.components.edit.documents')
              </div>           

            </div>
          </div>

          <textarea name="observations" class="hidden">{{ $project->observations }}</textarea>
          <input type="hidden" name="id_project" value="{{ $project->id }}">

          <div class="card-footer">
            <div class="mr-auto">
              <input type="button" class="btn btn-previous btn-fill btn-default btn-wd save-continue disabled" name="previous" value="Anterior">
            </div>
            <div class="ml-auto">
              <input type="button" class="btn btn-next btn-fill btn-primary btn-wd save-continue" name="next" value="Guardar y Siguiente">
              <input type="submit" class="btn btn-finish btn-fill btn-primary btn-wd show-content-observation" name="finish" value="Guardar" />
            </div>
            <div class="clearfix"></div>
          </div>

        {!! Form::close() !!}

        @include('backend.project.components.edit.documents_form_delete')

      </div>
    </div>

  </div>
</div>

@endsection

@section('javascript')
  <script>
    $(document).ready(function(){
    
      $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return (results ? results[1] || 0 : null);
      }

      if($.urlParam('project_cash_flow') != null)
      { 
        $('.cash_flow_link').removeClass('disabled-click');
        setTimeout(() => {
          $('.cash_flow_link').click();
          $('.cash_flow_link').addClass('disabled-click');
        }, 500);

        setTimeout(() => {
          $('.disabled-click').click(false);
        }, 1000);

      } else {
        $('.disabled-click').click(false);
      }

    });

    let collectionClients = {!! json_encode($clients,JSON_FORCE_OBJECT) !!};
    let projectID = {!! json_encode($project->id,JSON_FORCE_OBJECT) !!};

    function processDataBudget()
    {
      $.ajax({
        type: 'GET',
        url: '/json/projects/budget/'+projectID,
        dataType: 'json',
        success: function(response) {

          let projectBudgets = response.data;

          $("#content-data-budget tr").remove();

          $("#content-data-budget").append(`
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
            </tr>`);

          $.each(projectBudgets, function(key, collectionSubitem){

          let projectId       = {!! $project->id !!};

          $("#content-data-budget").append(`
          <tr class="b-c-gray items_content" id="item-${key}" data-id="${key}">
              <th>${collectionSubitem.item_number}</th>
              <th colspan="4" class="text-left pl-3">${collectionSubitem.item_name}</th>
              <th><input type="text"  name="item_subtotal" class="form-control text-center validate-number-decimal" disabled></th>
              <th></th>
              <th><input type="text"  name="item_total" class="form-control text-center validate-number-decimal" disabled></th>
              <th><input type="text"name="item_incidence" class="form-control text-center validate-tax" maxlength="6" disabled></th>
              <th>
                  <button
                      class="btn btn-danger removeItem removeItemAjax" 
                      data-idproject="${projectId}"
                      data-iditem="${key}"
                      style="padding: 0px 5px;"
                  >X</button>
              </th>
          </tr>`);

          let itemSubtotal   = 0;
          let itemTotal      = 0;
          let itemIncidene   = 0;

          $.each(collectionSubitem, function(i, budgetSubItem)
          {
            if(budgetSubItem.name_subitem != null)
            {      
              itemSubtotal   += budgetSubItem.subtotal;
              itemTotal      += budgetSubItem.total;
            
              $("#content-data-budget").append(`
                <tr id="subitem-${budgetSubItem.id_subitem}" data-id="${budgetSubItem.id_subitem}" data-iditem="${key}" class="sub_items_content">
                  
                  <input      type="hidden" name="budget[${budgetSubItem.id_subitem}][id]" value="${budgetSubItem.id}" />
                  <input      type="hidden" name="budget[${budgetSubItem.id_subitem}][id_item]" value="${key}" />

                  <td>${budgetSubItem.number_subitem}</td>
                  <td class="pl-3"><input class="form-control" value="${budgetSubItem.name_subitem}" disabled /></td>
                  <td><input type="text"  name="budget[${budgetSubItem.id_subitem}][unit]"      value="${budgetSubItem.unit}" class="form-control text-center" required /></td>
                  <td><input type="number"name="budget[${budgetSubItem.id_subitem}][quantity]"  value="${budgetSubItem.quantity}" maxlength="11"  id="quantity_${budgetSubItem.id_subitem}" data-idsubiteam="${budgetSubItem.id_subitem}" class="form-control text-center calculate-subtotal" required /></td>
                  <td><input type="text"  name="budget[${budgetSubItem.id_subitem}][unit_cost]" value="${budgetSubItem.unit_cost}" maxlength="14" id="unit_cost_${budgetSubItem.id_subitem}" data-idsubiteam="${budgetSubItem.id_subitem}" class="form-control text-center calculate-subtotal validate-number-decimal reset-value" required /></td>
                  <td><input type="text"  name="budget[${budgetSubItem.id_subitem}][subtotal]"  value="${budgetSubItem.subtotal}" maxlength="14"  id="subtotal_${budgetSubItem.id_subitem}" class="form-control text-center validate-number-decimal" readonly required /></td>
                  <td><input type="text"  name="budget[${budgetSubItem.id_subitem}][tax]"       value="${budgetSubItem.tax}" maxlength="6"        id="tax_${budgetSubItem.id_subitem}" data-idsubiteam="${budgetSubItem.id_subitem}" class="form-control text-center validate-tax calculate-subtotal reset-value" /></td>
                  <td><input type="text"  name="budget[${budgetSubItem.id_subitem}][total]"     value="${budgetSubItem.total}" maxlength="14"     id="total_${budgetSubItem.id_subitem}" class="form-control text-center validate-number-decimal" readonly required /></td>
                  <td><input type="text"  name="budget[${budgetSubItem.id_subitem}][incidence]" value="" maxlength="6"        id="incidence_${budgetSubItem.id_subitem}" data-idsubiteam="${budgetSubItem.id_subitem}" class="form-control text-center validate-tax" readonly /></td>
                  
                  <td class="text-center b-c-gray" style="vertical-align: inherit;">
                      <button
                      class="btn btn-danger removeSubItemAjax" 
                      data-idsubitem="subitem-${budgetSubItem.id_subitem}" 
                      data-id="${budgetSubItem.id}"
                      style="padding: 0px 5px;" 
                      >X</button>
                  </td>
                </tr>`);
            }
          });
            
            $("#content-data-budget").append(`
              <input type="hidden" name="item_subtotal_calc_${key}" value="${itemSubtotal}">
              <input type="hidden" name="item_total_calc_${key}" value="${itemTotal}">`);

          });

          $.each($(".validate-number-decimal"), function(){
            formatCurrency($(this), "blur");
          });

          $.each($(".validate-tax"), function(){
            formatTax($(this), "blur");
          });

          calculateTotales();
          removeItem();
          validateNumberDecimal();
          validateTax();
          calculateBudgetGlobalItems();
          deleteBudgetItem();
          deleteBudgetSubitem();
          isOpenBudget();
          projectJS.validateNumberDecimalAddAttr();
          projectJS.resetValueDefault();

          setTimeout(function(){ $('.spinner').hide(); }, 150);

        },
        error: function(xhr, status){
          console.log('Error: '+xhr);
        }
      });
    }

    function processDataRealCost()
    {
      $.ajax({
        type: 'GET',
        url: '/json/projects/real-cost/'+projectID,
        dataType: 'json',
        success: function(response){
          
          let projectRealCosts = response.data;

          $("#content-data-realcost tr").remove();

          $("#content-data-realcost").append(`
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
            </tr>`);

          $.each(projectRealCosts, function(key, collectionSubitem){

            let projectId       = {!! $project->id !!};

            $("#content-data-realcost").append(`
            <tr class="b-c-gray costreal-item" id="costreal-item-${key}" data-id="${key}">
              <th>${collectionSubitem.item_number}</th>
              <th colspan="4" class="text-left pl-3">${collectionSubitem.item_name}</th>
              <th><input type="text" name="costreal_budget_total"   class="form-control text-center" disabled></th>
              <th><input type="text" name="costreal_paid"           class="form-control text-center" disabled></th>
              <th><input type="text" name="costreal_balance_item"   class="form-control text-center" disabled></th>
              <th></th>
              <th></th>
              <th>
                  <button
                      class="btn btn-danger removeCostRealItemAjax" 
                      data-idproject="${projectId}"
                      data-iditem="${key}"
                      style="padding: 0px 5px;"
                  >X</button>
              </th>
            </tr>`);

            let budgetTotal = 0;
            let paid        = 0;
            let balanceItem = 0;

            $.each(collectionSubitem, function(i, costReal)
            {
              if(costReal.name_subitem != null)
              {
                budgetTotal += costReal.budget_total;
                paid += costReal.paid;
                balanceItem += costReal.balance_item;


                $("#content-data-realcost").append(`
                  <tr id="costreal-subitem-${costReal.id_subitem}" data-iditem="${key}" class="costreal-subitem">
                              
                      <input      type="hidden" name="real_cost[${costReal.id_subitem}][id]" value="${costReal.id}" />
                      <input      type="hidden" name="real_cost[${costReal.id_subitem}][id_item]" value="${key}" />

                      <td>${costReal.number_subitem}</td>
                      <td class="pl-3"><input class="form-control" value="${costReal.name_subitem}" disabled /></td>
                      <td><input type="number"name="real_cost[${costReal.id_subitem}][quantity]" value="${costReal.quantity}"         id="quantity_realcost_${costReal.id_subitem}" data-idsubiteam="${costReal.id_subitem}" maxlength="11" class="form-control text-center calculate-subtotal-realcost" /></td>
                      <td><input type="text"  name="real_cost[${costReal.id_subitem}][unit_cost]"value="${costReal.unit_cost}"        id="unit_cost_realcost_${costReal.id_subitem}" data-idsubiteam="${costReal.id_subitem}" maxlength="14" class="form-control text-center validate-number-decimal calculate-subtotal-realcost reset-value" /></td>
                      <td><input type="text"  name="real_cost[${costReal.id_subitem}][tax]" value="${costReal.tax}"                   id="tax_realcost_${costReal.id_subitem}" data-idsubiteam="${costReal.id_subitem}" maxlength="6" class="form-control text-center validate-tax calculate-subtotal-realcost reset-value" /></td>
                      <td><input type="text"  name="real_cost[${costReal.id_subitem}][budget_total]" value="${costReal.budget_total}" id="budget_total_realcost_${costReal.id_subitem}" maxlength="14" class="form-control text-center validate-number-decimal" readonly /></td>
                      <td><input type="text"  name="real_cost[${costReal.id_subitem}][paid]" value="${costReal.paid}"        maxlength="14" class="form-control text-center validate-number-decimal costreal-subitem-paid" readonly /></td>
                      <td><input type="text"  name="real_cost[${costReal.id_subitem}][balance_item]" value="${costReal.balance_item}" id="balance_item_realcost_${costReal.id_subitem}" maxlength="14" class="form-control text-center validate-number-decimal" readonly /></td>
                      <td><input type="text"  name="real_cost[${costReal.id_subitem}][budget_variation_pesos]" value="${costReal.budget_variation_pesos}" id="budget_variation_pesos_realcost_${costReal.id_subitem}" maxlength="14" class="form-control text-center validate-number-decimal" readonly /></td>
                      <td><input type="text"  name="real_cost[${costReal.id_subitem}][budget_variation_tax]" value="${costReal.budget_variation_tax}"    maxlength="6" class="form-control text-center validate-tax" readonly /></td>
                      
                      <td class="text-center b-c-gray" style="vertical-align: inherit;">
                          <button
                              class="btn btn-danger removeCostRealSubItemAjax" 
                              data-id="${costReal.id}"
                              data-subitemid="${costReal.id_subitem}"
                              style="padding: 0px 5px;" 
                          >X</button>
                      </td>
                  </tr>
                  `);
              }
            });
            
            $("#content-data-realcost").append(`
                <input type="text" id="add-subitems-item-${key}" class="validate-number-decimal" style="display: none!important;" name="costreal_budget_total_calc_${key}"   value="${budgetTotal}">
                <input type="text" class="validate-number-decimal" style="display: none!important;" name="costreal_paid_calc_${key}"           value="${paid}">
                <input type="text" class="validate-number-decimal" style="display: none!important;" name="costreal_balance_item_calc_${key}"   value="${balanceItem}">`);

          });

          $.each($(".validate-number-decimal"), function(){
            formatCurrency($(this), "blur");
          });

          $.each($(".validate-tax"), function(){
            formatTax($(this), "blur");
          });

          // active list select-subitems in seguimiento
          $('.select-subitems').first().removeClass('hidden');

          calculateTotalesRealCost();
          removeItemRealCost();
          validateNumberDecimal();
          validateTax();
          calculateCostRealGlobalItems();
          isOpenBudget();
          deleteCostRealItem();
          deleteCostRealSubItem();
          projectJS.validateNumberDecimalAddAttr();
          projectJS.resetValueDefault();

          setTimeout(function(){ $('.spinner').hide(); }, 150);

        },
        error: function(xhr, status){
          console.log('Error: '+xhr);
        }
      });
    }
  </script>
  <script src="{{ asset('/js/project.js') }}"></script>
@endsection
