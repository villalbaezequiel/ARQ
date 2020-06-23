$(document).ready(function() {

    $('a').click(function(e) {
        if(!$(this).hasClass('none-alert')) {
          var parentForm = $(this).parents('form');
          if(parentForm.length != 1 || $(this).hasClass('back-history')) {
            e.preventDefault();
            alertLeaveForm(this);
          }
        }
    });

    // input validate
    let rules = {
          start_date: {
            required: true
          },
          finish_date: {
            required: true
          },
          name: {
            required: true
          },
          id_client: {
            required: true
          }
          
    };
  
    arqJs.initMaterialWizard(rules);
  
    $('.show-content-observation').click(function(e) {
      e.preventDefault();
  
      Swal.fire({
        'title': 'Observaciones',
        input: 'textarea',
        inputValue: $('textarea[name=observations]').val(),
        showCancelButton: true
      })
      .then((result) => {
        if(result.dismiss == "cancel"){
          return false;
        }
  
        $('textarea[name=observations]').val(result.value);
        $('#logic_project').submit();
      });
    });

    calculateTotales();
    removeItem();
    validateNumberDecimal();
    calculateBudgetGlobalItems();
    calculateCostRealGlobalItems();
    deleteBudgetItem();
    deleteBudgetSubitem();
    setOpenBudget();
    isOpenBudget();
    deleteCostRealItem();
    deleteCostRealSubItem();
    deleteProjectProvider();

});

function addGroupItems(e)
{
    e.preventDefault();
    let idItem      = $("select[name=items]").find(':selected').val();
    let nameItem    = $("select[name=items]").find(':selected').text();

    let checkItem   = $('#content-items tbody tr');
    let issetItem   = false;

    if(checkItem.length){
      $.each(checkItem, function(i, v){
        if($(this).data('iditem') == idItem) {
          issetItem = true;
        }  
      });
    }

    if(issetItem == false)
    {
      // add all subitems
      let dataSubItems  = [];
      $("#select-subitems-"+idItem+" select[name=sub_items] option").each(function(){
          dataSubItems.push({
            name: $(this).text(),
            id: $(this).val()
          });
      });

      generateTemplateItems(idItem, nameItem, dataSubItems);

      setSelectedOptions();
    } else {
      Swal.fire({
        title: 'Rubro Existente en la Planilla!',
        type: 'info',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
      });
    }
}

function generateTemplateItems(idItem, nameItem, dataSubItems)
{
  let items = `
    <tr class="b-c-gray" id="item-${idItem}">
      <th colspan="4" class="text-left pl-3">${nameItem}</th>
      <th><input type="text" name="item_subtotal" class="form-control validate-number-decimal" disabled></th>
      <th></th>
      <th><input type="text" name="item_total" class="form-control validate-number-decimal" disabled></th>
      <th><input type="text" name="item_incidence" class="form-control validate-number-decimal" maxlength="6" disabled></th>
      <th>
        <button
          class="btn btn-danger removeItem" 
          data-iditem="${idItem}" 
          style="padding: 0px 5px;"
        >X</button>
      </th>
    </tr>`;

  $.each(dataSubItems, function(i, v){
        
    items += `
      <tr id="subitem-${v.id}" data-iditem="${idItem}" class="sub_items_content">
      
        <input      type="hidden" name="budget[${v.id}][id_item]" value="${idItem}" />

        <td class="pl-3"><input class="form-control" value="${v.name}" disabled /></td>
        <td><input type="text"    name="budget[${v.id}][unit]"      class="form-control" required /></td>
        <td><input type="text"  name="budget[${v.id}][quantity]"  maxlength="11"  id="quantity_${v.id}" data-idsubiteam="${v.id}" class="form-control calculate-subtotal validate-number-decimal" required /></td>
        <td><input type="text"  name="budget[${v.id}][unit_cost]" maxlength="10"  id="unit_cost_${v.id}" data-idsubiteam="${v.id}" class="form-control calculate-subtotal validate-number-decimal" required /></td>
        <td><input type="text"  name="budget[${v.id}][subtotal]"  maxlength="10"  id="subtotal_${v.id}" class="form-control validate-number-decimal" readonly required /></td>
        <td><input type="text"    name="budget[${v.id}][tax]"       maxlength="6"   id="tax_${v.id}" value="0" data-idsubiteam="${v.id}" class="form-control validate-number-decimal calculate-subtotal" required /></td>
        <td><input type="text"  name="budget[${v.id}][total]"     maxlength="10"  id="total_${v.id}" class="form-control validate-number-decimal" readonly required /></td>
        <td></td>
        
        <td class="text-center b-c-gray" style="vertical-align: inherit;">
          <button
            class="btn btn-danger" 
            data-idsubitem="subitem-${v.id}" 
            style="padding: 0px 5px;" 
            onClick="$('#subitem-${v.id}').remove(); return false;"
          >X</button>
        </td>

      </tr>
    `;
  });
  
  $('#content-items tbody').append(items);

  calculateTotales();
  removeItem();
  validateNumberDecimal();
}

function setSelectedOptions()
{
  $('select[name=items] option:selected').prop("selected", false);
  $('select[name=sub_items] option:selected').prop("selected", false);
  $('.selectpicker').selectpicker('refresh');
}

function calculateTotales(){
  $(".calculate-subtotal").keyup(function(){
    let idSubItem = $(this).data('idsubiteam');

    let subTotal  = ( parseFloat($('#quantity_'+idSubItem).val()) * parseFloat($('#unit_cost_'+idSubItem).val()) );
    let total  = ( subTotal + ((subTotal * parseFloat($('#tax_'+idSubItem).val())) / 100) );

    $('#subtotal_'+idSubItem).val(subTotal);
    $('#total_'+idSubItem).val(total);
  });
}

function removeItem()
{
  $('.removeItem').click(function() {
    let iditem       = $(this).data('iditem');
    let subItemsContent  = $('.sub_items_content');

    $('#item-'+iditem).remove();

    $.each(subItemsContent, function(i, v){
      if($(this).data('iditem') == iditem) {
        $(this).remove();
      }
    });
    
    return false;
  });
}

function validateNumberDecimal(){
  $(".validate-number-decimal").on({
    "focus": function(event) {
      $(event.target).select();
    },
    "keyup": function(event) {
      $(event.target).val(function(index, value) {
        return (value.replace(/\D/g, "")
          .replace(/([0-9])([0-9]{2})$/, '$1.$2')
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
      });
    }
  });
}

function calculateBudgetGlobalItems(){
  let itemsContent    = $('.items_content');
  let globalSubtotal  = 0,
      globalTotal     = 0;

  $.each(itemsContent, function(i, v){
    let idItem    = $(this).data('id');
    let subTotal  = $(`input[name=item_subtotal_calc_${idItem}]`).val();
    let total     = $(`input[name=item_total_calc_${idItem}]`).val();

    $(this).find('input[name="item_subtotal"]').val(subTotal);
    $(this).find('input[name="item_total"]').val(total);

    globalSubtotal  += parseFloat(subTotal);
    globalTotal     += parseFloat(total);
  });

  $('input[name="global_subtotal"]').val(globalSubtotal);
  $('input[name="global_total"]').val(globalTotal);

  $.each(itemsContent, function(i, v){
    let idItem      = $(this).data('id');
    let totalGlobal = $('input[name="global_total"]').val();
    let totalItem   = $(`input[name=item_total_calc_${idItem}]`).val();
    let incidence   = ( parseFloat(totalItem) / parseFloat(totalGlobal) ) * 100;

    $(this).find('input[name="item_incidence"]').val(incidence.toFixed(2));
  });
}

function calculateCostRealGlobalItems(){
  let itemsContent      = $('.costreal-item');
  let globalbudgetTotal = 0,
      globalpaid        = 0,
      globalbalancePaid = 0,
      globalbalanceItem = 0;

  $.each(itemsContent, function(i, v){
    let idItem        = $(this).data('id');
    let budgetTotal   = $(`input[name=costreal_budget_total_calc_${idItem}]`).val();
    let paid          = $(`input[name=costreal_paid_calc_${idItem}]`).val();
    let balancePaid   = $(`input[name=costreal_balance_paid_calc_${idItem}]`).val();
    let balanceItem   = $(`input[name=costreal_balance_item_calc_${idItem}]`).val();

    $(this).find('input[name="costreal_budget_total"]').val(budgetTotal);
    $(this).find('input[name="costreal_paid"]').val(paid);
    $(this).find('input[name="costreal_balance_paid"]').val(balancePaid);
    $(this).find('input[name="costreal_balance_item"]').val(balanceItem);

    globalbudgetTotal += parseFloat(budgetTotal);
    globalpaid        += parseFloat(paid);
    globalbalancePaid += parseFloat(balancePaid);
    globalbalanceItem += parseFloat(balanceItem);
  });

  $('input[name="global_costreal_budgetTotal"]').val(globalbudgetTotal);
  $('input[name="global_costreal_paid"]').val(globalpaid);
  $('input[name="global_costreal_balancePaid"]').val(globalbalancePaid);
  $('input[name="global_costreal_balanceItem"]').val(globalbalanceItem);

}

function deleteBudgetItem()
{
  $('.removeItemAjax').click(function(){

    let data = {
      id_item: $(this).data('iditem'),
      id_project: $(this).data('idproject')
    }

    $.ajax({
      type: 'GET',
      url: '/json/budgets/delete-item',
      data: data,
      success: function(data){
        if(data.status){
          Swal.fire({
            title: 'Rubro eliminado con Exito!',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          });
        }
      },
      error: function(xhr, status){
        console.log('Error: '+xhr);
      }
    });

    return false;

  });
}

function deleteBudgetSubitem()
{
  $('.removeSubItemAjax').click(function(){

    $('#'+$(this).data('idsubitem')).remove();

    let data = {
      id: $(this).data('id')
    }

    $.ajax({
      type: 'GET',
      url: '/json/budgets/delete-subitem',
      data: data,
      success: function(data){
        if(data.status){
          Swal.fire({
            title: 'Sub Rubro eliminado con Exito!',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          });
        }
      },
      error: function(xhr, status){
        console.log('Error: '+xhr);
      }
    });

    return false;

  });
}

function deleteCostRealItem()
{
  $('.removeCostRealItemAjax').click(function(){

    let iditem          = $(this).data('iditem');
    let costrealSubitem = $('.costreal-subitem');

    let data = {
      id_item: iditem,
      id_project: $(this).data('idproject')
    }

    $.ajax({
      type: 'GET',
      url: '/json/costreal/delete-item',
      data: data,
      success: function(data){
        if(data.status){
          Swal.fire({
            title: 'Rubro eliminado con Exito!',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          });

          $('#costreal-item-'+iditem).remove();

          $.each(costrealSubitem, function(i, v){
            if($(this).data('iditem') == iditem) {
              $(this).remove();
            }
          });
        
        }
      },
      error: function(xhr, status){
        console.log('Error: '+xhr);
      }
    });

    return false;

  });
}

function deleteCostRealSubItem()
{
  $('.removeCostRealSubItemAjax').click(function(){
    
    $('#costreal-subitem-'+$(this).data('id')).remove();

    let data = {
      id: $(this).data('id')
    }

    $.ajax({
      type: 'GET',
      url: '/json/costreal/delete-subitem',
      data: data,
      success: function(data){
        if(data.status){
          Swal.fire({
            title: 'Sub Rubro eliminado con Exito!',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          });
        }
      },
      error: function(xhr, status){
        console.log('Error: '+xhr);
      }
    });

    return false;

  });
}

function setOpenBudget()
{
  $('.open-budget').click(function(){

    let data = {
      id_project: $(this).data('idproject')
    }

    $.ajax({
      type: 'GET',
      url: '/json/budgets/open',
      data: data,
      success: function(data){
        if(data.status){
          Swal.fire({
            title: 'Se ha Aprobado el Presupuesto con Exito!',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          })
          .then((result) => {
            location.reload()
          });
        }
      },
      error: function(xhr, status){
        console.log('Error: '+xhr);
      }
    });

    return false;

  });
}

function isOpenBudget()
{
  let isOpenBudget = $('input[name=open_budget]').val();

  if(isOpenBudget == 0)
  {
    $('#content-items input').prop('disabled', true);
    $('#content-items .btn-danger').remove();
  }
}

function deleteProjectProvider()
{
  $('.removeProjectProvider').click(function(){

    $(this).parents('tr').remove();

    let data = {
      id: $(this).data('id')
    }

    $.ajax({
      type: 'GET',
      url: '/json/project-provider',
      data: data,
      success: function(data){
        if(data.status){
          Swal.fire({
            title: 'Proveedor eliminado con Exito!',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          });
        }
      },
      error: function(xhr, status){
        console.log('Error: '+xhr);
      }
    });

    return false;

  });
}

function addNewBudget(e)
{
    e.preventDefault();

    if($('input[name=name_budget]').val())
    {
      
      $(".budget-event-hidden").addClass('hidden');
      $(".budget-event-show").removeClass('hidden');

      $('input[name=name_budget]').parent('div').removeClass('col-md-5').addClass('col-md-11');

    } else {
      Swal.fire({
        title: 'Debes ingresar un Nombre para el Presupuesto!',
        type: 'info',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
      });
    }
}

function addCashFlow(e)
{
    e.preventDefault();

    let data  = {};
    let type  = $('select[name=cash_flow_type]').find(':selected').val();

    if(type == "entry")
    {
      data = {
          id_project    : $('input[name=id_project]').val(),
          date          : $('input[name=cash_flow_date]').val(),
          type          : $('select[name=cash_flow_type]').find(':selected').val(),
          amount        : $('input[name=cash_flow_amount]').val(),
          tax           : "0",
          payment_type  : $('select[name=cash_flow_payment_type]').find(':selected').val(),
          id_item       : "0",
          id_subitem    : "0",
          detail        : $('select[name=cash_flow_detail]').find(':selected').val(),
          id_provider   : "0",
          clarification : $('input[name=cash_flow_clarification]').val(),
          id_user       : $('select[name=cash_flow_id_user]').find(':selected').val(),
          id_client     : $('select[name=cash_flow_id_client]').find(':selected').val()  
      }
    } else if(type == "egress" || type == "direct_payment") {

      data = {
        id_project    : $('input[name=id_project]').val(),
        date          : $('input[name=cash_flow_date]').val(),
        type          : $('select[name=cash_flow_type]').find(':selected').val(),
        amount        : $('input[name=cash_flow_amount]').val(),
        tax           : "0",
        payment_type  : $('select[name=cash_flow_payment_type]').find(':selected').val(),
        id_item       : $('select[name=cash_flow_id_item]').find(':selected').val(),
        id_subitem    : $('select[name=cash_flow_id_subitem]').find(':selected').val(),
        detail        : $('select[name=cash_flow_detail]').find(':selected').val(),
        id_provider   : $('select[name=cash_flow_id_provider]').find(':selected').val(),
        clarification : $('input[name=cash_flow_clarification]').val(),
        id_user       : $('select[name=cash_flow_id_user]').find(':selected').val(),
        id_client     : "0"
      }
    }

    if(validateObjectValues(data))
    {
      $.ajax({
        type: 'POST',
        url: '/project_cash_flow',
        data: data,
        success: function(data){
          if(data.status){
            Swal.fire({
              title: 'Flujo de Caja agregado con Exito!',
              type: 'info',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Aceptar'
            })
            .then((result) => {
              location.reload()
            });
          }
        },
        error: function(xhr, status){
          console.log('Error: '+xhr);
        }
      });
    } else {
      Swal.fire({
        title: 'Debes completar los Campos Requeridos!',
        type: 'info',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
      })
    }

    return;
}

function validateObjectValues(obj)
{
  for(var o in obj)
      if(!obj[o]) return false;
    
  return true;
}

function getTemplateHistoryProvider(){
  let formSwal = `<div class="table-responsive"><table class="table table-striped">
                    <thead>
                        <tr class="b-c-gray">
                          <th class="font-weight-bold-600">Avance</th>
                          <th class="font-weight-bold-600">Fecha</th>
                          <th class="font-weight-bold-600">Monto</th>
                          <th class="font-weight-bold-600">Impuesto</th>
                          <th class="font-weight-bold-600">Forma de pago</th>
                          <th class="font-weight-bold-600">Rubro</th>
                          <th class="font-weight-bold-600">Sub-Rubro</th>
                          <th class="font-weight-bold-600">Detalle</th>
                          <th class="font-weight-bold-600">Aclaración</th>
                        </tr>
                    </thead>
                    <tbody class="content-history-provider">
                     </tbody>
                  </table></div>
                  <div class="row">
                    <div class="col-md-12">
                      <p class="text-left">Total Avanzado: <span class="total-history-provider"></span></p>
                      <p class="text-left">Recibi la suma de Pesos ........ en concepto de avance N* ........</p>
                    </div>
                    <div class="col-md-12">
                      <form action="#">
                        <input type="hidden" name="_token" value="">
                        <input type="hidden" name="id_project" value="">
                        <input type="hidden" name="id_provider" value="">
                        <input type="submit" class="btn btn-primary" value="Descargar Historial Proveedor">
                      </form>
                    </div>
                  </div>
                  `;

  return formSwal;
}

let projectJS = {

  getProvidersFromItem: (item, selectProviders) => {

    let providers = $(`select[name=${selectProviders}]`);

    if(!$(item).val()) {
      providers.empty();
      return false;
    }

    $.ajax({
      type: 'GET',
      url: '/json/providers/item/'+$(item).val(),
      dataType: 'json',
      success: function(result){
        
        let data      = result.data;
          
        providers.empty();

        if(data.length >= 1)
        {
            providers.append("<option value=''>Seleccionar</option>");
            $.each(data, function(i,v){
              providers.append(`<option value="${v.id}">${v.name}</option>`);
            });

            $('.selectpicker').selectpicker('refresh');
        
        } else {
          providers.empty();
          Swal.fire({
            title: 'No hay Proveedores para el Rubro seleccionado!',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          });
          $('.selectpicker').selectpicker('refresh');
        }
      },
      error: function(xhr, status){
        providers.empty();
        Swal.fire({
          title: 'Hubo un error al Consultar los Proveedores!',
          type: 'warning',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar'
        });
        $('.selectpicker').selectpicker('refresh');
      }
    });
  }, 
  
  searchProviders: (e, selectItem, contentProvidersList) => {

    e.preventDefault();

    let contentProviders = $(`.${contentProvidersList}`);
    
    let idProject   = $('input[name=id_project]').val();
    let itemId      = $(`select[name=${selectItem}]`).find(':selected').val();

    if(itemId)
    {
      $.ajax({
        type: 'GET',
        url: `/json/project_providers/item/${idProject}/${itemId}`,
        dataType: 'json',
        success: function(result){
          
          let data      = result.data;
            
          contentProviders.empty();
  
          if(data.length >= 1)
          {
            $.each(data, function(i,v){

              contentProviders.append(`
                <tr>
                  <td class="font-weight-bold-600">${v.id}</td>
                  <td>${v.name}</td>
                  <td class="font-weight-bold-600">${v.amount}</td>        
                  <td class="text-center" style="vertical-align: inherit;">
                    <button class="btn btn-link btn-warning btn-just-icon show" onclick="projectJS.getHistoryProvider(event, ${idProject}, ${v.id_provider}, '${v.name}')">
                      <i class="material-icons">visibility</i>
                    </button>
                  </td>
                </tr>`);
            });        
          } else {
            Swal.fire({
              title: 'No hay Proveedores para el Rubro seleccionado!',
              type: 'info',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Aceptar'
            });
            contentProviders.empty();
          }
        },
        error: function(xhr, status){
          providers.empty();
          Swal.fire({
            title: 'Hubo un error al Consultar los Proveedores!',
            type: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          });
        }
      });
    } else {
      Swal.fire({
        title: 'Debes seleccionar un Rubro!',
        type: 'info',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
      });
    }
  
    return;
  },

  getHistoryProvider: (e, idProject, idProvider, nameProvider) => {
      
    e.preventDefault();

    if(idProject && idProvider)
    {
      $.ajax({
        type: 'GET',
        url: `/json/project_provider_history/${idProject}/${idProvider}`,
        dataType: 'json',
        success: function(result){
          
          let data      = result.data;
          let total     = result.total;
          
          if(data.length > 0)
          {
            Swal.fire({
              title: `Proveedor: ${nameProvider}`,
              html: getTemplateHistoryProvider(),
              confirmButtonClass: "btn btn-success",
              showCancelButton: false,
              customClass: 'swal-wide',
              buttonsStyling: !1,
              onBeforeOpen: () => {
  
                // let dataExport = [];

                $(".total-history-provider").text(total);
                
                
                $.each(data, function(i,v){
  
                  $(".content-history-provider").append(`<tr>
                                    <td class="font-weight-bold-600">${i+1}</td>
                                    <td>${v.date}</td>
                                    <td>${v.amount}</td>
                                    <td>${v.tax}</td>
                                    <td>${v.payment_type}</td>
                                    <td>${v.id_item}</td>
                                    <td>${v.id_subitem}</td>
                                    <td>${v.detail}</td>
                                    <td>${v.clarification}</td>
                                </tr>`);
  
                // let valorTotalHoraHsExtra = ( parseFloat(calculateHsExtras(countHour, v.agreed_hours)).toFixed(3) * valorHoraHsExtra );
  
                //   dataExport.push({
                //     fecha: getDatePickerFormat(v.start, true),
                //     rol: v.rol,
                //     inicio: parseDate(start.getHours())+':'+parseDate(start.getMinutes()),
                //     final: parseDate(finish.getHours())+':'+parseDate(finish.getMinutes()),
                //     horas_pactadas: v.agreed_hours,
                //     horas_extras: (countHour - v.agreed_hours),
                //     valor_hora: "$" + valorHoraHsExtra.toFixed(2),
                //     valor_total: "$" + valorTotalHoraHsExtra.toFixed(2)
                //   });
  
              });
  
                // $("#form_hs_extras input[name=_token]").val($("#logic_project input[name=_token]").val());
                // $("#form_hs_extras input[name=name_team]").val(nameTeam);
                // $("#form_hs_extras input[name=id_project_provider]").val(id);
                // $("#form_hs_extras input[name=hs_extras]").val(JSON.stringify(dataExport));
  
              }
            }); 
          }

        },
        error: function(xhr, status){
          Swal.fire({
            title: 'Hubo un error al Consultar Historial del Proveedor!',
            type: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          });  
        }
      });
    } else {
      Swal.fire({
        title: 'Hubo un error al Consultar Historial del Proveedor!',
        type: 'warning',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
      });
    }
  
    return;
  }

}