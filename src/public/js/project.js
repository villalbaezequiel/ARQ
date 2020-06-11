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
    calculateGlobalItem();
    deleteBudgetItem();
    deleteBudgetSubitem();
    setOpenBudget();
    isOpenBudget();

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
      console.log("ya existe!");
    }
}

function generateTemplateItems(idItem, nameItem, dataSubItems)
{
  let items = `
    <tr class="b-c-gray" id="item-${idItem}">
      <th colspan="4" class="text-left pl-3">${nameItem}</th>
      <th><input type="number" name="item_subtotal" class="form-control validate-number-decimal" readonly></th>
      <th></th>
      <th><input type="number" name="item_total" class="form-control validate-number-decimal" readonly></th>
      <th><input type="number" name="item_incidence" class="form-control validate-number-decimal" maxlength="6" readonly></th>
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
        <td><input type="number"  name="budget[${v.id}][quantity]"  maxlength="11"  id="quantity_${v.id}" data-idsubiteam="${v.id}" class="form-control calculate-subtotal" required /></td>
        <td><input type="number"  name="budget[${v.id}][unit_cost]" maxlength="10"  id="unit_cost_${v.id}" data-idsubiteam="${v.id}" class="form-control calculate-subtotal" required /></td>
        <td><input type="number"  name="budget[${v.id}][subtotal]"  maxlength="10"  id="subtotal_${v.id}" class="form-control" readonly required /></td>
        <td><input type="text"    name="budget[${v.id}][tax]"       maxlength="6"   id="tax_${v.id}" value="0" data-idsubiteam="${v.id}" class="form-control validate-number-decimal calculate-subtotal" required /></td>
        <td><input type="number"  name="budget[${v.id}][total]"     maxlength="10"  id="total_${v.id}" class="form-control" readonly required /></td>
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

function calculateGlobalItem(){
  let itemsContent    = $('.items_content');
  let globalSubtotal  = 0;
  let globalTotal  = 0;

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

  console.log(isOpenBudget);

  if(isOpenBudget == 0)
  {
    $('#content-items input').prop('disabled', true);
    $('#content-items .btn-danger').remove();
  }
}