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

    $.each($(".validate-number-decimal"), function(){
      formatCurrency($(this), "blur");
    });

    $.each($(".validate-tax"), function(){
      formatTax($(this), "blur");
    });

    calculateTotales();
    calculateTotalesRealCost();
    removeItem();
    removeItemRealCost();
    validateNumberDecimal();
    validateTax();
    calculateBudgetGlobalItems();
    calculateCostRealGlobalItems();
    deleteBudgetItem();
    deleteBudgetSubitem();
    setOpenBudget();
    isOpenBudget();
    deleteCostRealItem();
    deleteCostRealSubItem();
    projectJS.validateNumberDecimalAddAttr();

});

function addGroupItems(e)
{
    e.preventDefault();
    let idItem      = $("select[name=items]").find(':selected').val();
    let numberItem  = $("select[name=items]").find(':selected').data('number');
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
            number: $(this).data('number'),
            id: $(this).val()
          });
      });

      generateTemplateItems(idItem, numberItem, nameItem, dataSubItems);

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

function addGroupItemsRealCost(e)
{
    e.preventDefault();
    let idItem      = $("select[name=items]").find(':selected').val();
    let nameItem    = $("select[name=items]").find(':selected').text();

    let checkItem   = $('#content-real-cost tbody tr');
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

      generateTemplateItemsRealCost(idItem, nameItem, dataSubItems);

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

function generateTemplateItems(idItem, numberItem, nameItem, dataSubItems)
{
  let items = `
    <tr class="b-c-gray" id="item-${idItem}">
      <th>${numberItem}</th>
      <th colspan="4" class="text-left pl-3">${nameItem}</th>
      <th><input type="text" name="item_subtotal" class="form-control text-center validate-number-decimal" disabled></th>
      <th></th>
      <th><input type="text" name="item_total" class="form-control text-center validate-number-decimal" disabled></th>
      <th><input type="text" name="item_incidence" class="form-control text-center validate-tax" maxlength="6" disabled></th>
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

        <td>${v.number}</td>
        <td class="pl-3"><input class="form-control" value="${v.name}" disabled /></td>
        <td><input type="text"    name="budget[${v.id}][unit]"      class="form-control text-center" required /></td>
        <td><input type="number"  name="budget[${v.id}][quantity]"  maxlength="11"  id="quantity_${v.id}" value="1" data-idsubiteam="${v.id}" class="form-control text-center calculate-subtotal" required /></td>
        <td><input type="text"    name="budget[${v.id}][unit_cost]" maxlength="14"  id="unit_cost_${v.id}" data-idsubiteam="${v.id}" value="$1" class="form-control text-center calculate-subtotal validate-number-decimal" required /></td>
        <td><input type="text"    name="budget[${v.id}][subtotal]"  maxlength="14"  id="subtotal_${v.id}"  value="$1" class="form-control text-center validate-number-decimal" readonly required /></td>
        <td><input type="text"    name="budget[${v.id}][tax]"       maxlength="6"   id="tax_${v.id}" value="%0" data-idsubiteam="${v.id}" class="form-control text-center validate-tax calculate-subtotal" /></td>
        <td><input type="text"    name="budget[${v.id}][total]"     maxlength="14"  id="total_${v.id}" value="$1" class="form-control text-center validate-number-decimal" readonly required /></td>
        <td><input type="text"    name="budget[${v.id}][incidence]" maxlength="6"   id="incidence_${v.id}" data-idsubiteam="${v.id}" class="form-control text-center validate-tax" readonly /></td>
        
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
  validateTax();
  projectJS.validateNumberDecimalAddAttr();
}

function generateTemplateItemsRealCost(idItem, nameItem, dataSubItems)
{
  let items = `
    <tr class="b-c-gray" id="costreal-item-${idItem}" data-id="${idItem}">
    <th colspan="4" class="text-left pl-3">${nameItem}</th>
    <th><input type="text" name="costreal_budget_total"   class="form-control" disabled></th>
    <th><input type="text" name="costreal_paid"           class="form-control" disabled></th>
    <th><input type="text" name="costreal_balance_paid"   class="form-control" disabled></th>
    <th><input type="text" name="costreal_balance_item"   class="form-control" disabled></th>
    <th></th>
    <th></th>
    <th>
        <button
          class="btn btn-danger removeItemRealCost" 
          data-iditem="${idItem}" 
          style="padding: 0px 5px;"
        >X</button>
      </th>
    </tr>`;

  $.each(dataSubItems, function(i, v){
        
    items += `
      <tr id="costreal-subitem-${v.id}" data-iditem="${idItem}" class="costreal-subitem">
      
        <input      type="hidden" name="real_cost[${v.id}][id_item]" value="${idItem}" />

        <td class="pl-3"><input class="form-control" value="${v.name}" disabled /></td>
        <td><input type="number"  name="real_cost[${v.id}][quantity]" value="1"      id="quantity_realcost_${v.id}" maxlength="11" data-idsubiteam="${v.id}"  class="form-control calculate-subtotal-realcost" /></td>
        <td><input type="text"    name="real_cost[${v.id}][unit_cost]" value="$1"    id="unit_cost_realcost_${v.id}"  maxlength="14" data-idsubiteam="${v.id}" class="form-control validate-number-decimal calculate-subtotal-realcost" /></td>
        <td><input type="number"  name="real_cost[${v.id}][tax]" value="0"           id="tax_realcost_${v.id}"  maxlength="5" data-idsubiteam="${v.id}" class="form-control calculate-subtotal-realcost" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length == 3) return false;" /></td>
        <td><input type="text"    name="real_cost[${v.id}][budget_total]" value="$1" id="budget_total_realcost_${v.id}"  maxlength="14"  class="form-control validate-number-decimal" readonly /></td>
        <td><input type="text"    name="real_cost[${v.id}][paid]" maxlength="14"  class="form-control validate-number-decimal" readonly /></td>
        <td><input type="text"    name="real_cost[${v.id}][balance_paid]" maxlength="14"  class="form-control validate-number-decimal" readonly/></td>
        <td><input type="text"    name="real_cost[${v.id}][balance_item]" maxlength="14" id="balance_item_realcost_${v.id}" class="form-control validate-number-decimal" readonly /></td>
        <td><input type="text"    name="real_cost[${v.id}][budget_variation_pesos]" id="budget_variation_pesos_realcost_${v.id}" maxlength="14"  class="form-control validate-number-decimal" readonly /></td>
        <td><input type="number"  name="real_cost[${v.id}][budget_variation_tax]" maxlength="5"   class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length == 3) return false;" readonly /></td>

        <td class="text-center b-c-gray" style="vertical-align: inherit;">
          <button
            class="btn btn-danger" 
            data-idsubitem="costreal-subitem-${v.id}" 
            style="padding: 0px 5px;" 
            onClick="$('#costreal-subitem-${v.id}').remove(); return false;"
          >X</button>
        </td>

      </tr>
    `;
  });
  
  $('#content-real-cost tbody').append(items);

  calculateTotalesRealCost();
  removeItemRealCost();
  validateNumberDecimal();
  validateTax();
  projectJS.validateNumberDecimalAddAttr();
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

      let subTotal  = parseInt($('#quantity_'+idSubItem).val()) * projectJS.parseFloatPrices($('#unit_cost_'+idSubItem).val());
      let total     = parseFloat( subTotal + ( (subTotal * projectJS.parseFloatPrices($('#tax_'+idSubItem).val())) / 100) );

      if(!subTotal) subTotal = parseFloat(1);
      if(!total) total = parseFloat(1);

      $('#subtotal_'+idSubItem).val(subTotal);
      $('#total_'+idSubItem).val(total);

      formatCurrency($('#subtotal_'+idSubItem), 'blur');
      formatCurrency($('#total_'+idSubItem), 'blur');
  });
}

function calculateTotalesRealCost()
{
  $(".calculate-subtotal-realcost").keyup(function(){

    let idSubItem = $(this).data('idsubiteam');

    let subTotal  = parseInt($('#quantity_realcost_'+idSubItem).val()) * projectJS.parseFloatPrices($('#unit_cost_realcost_'+idSubItem).val());
    let total     = parseFloat( subTotal + ( (subTotal * projectJS.parseFloatPrices($('#tax_realcost_'+idSubItem).val())) / 100) );

    if(!subTotal) subTotal = parseFloat(1);
    if(!total) total = parseFloat(1);

    $('#budget_total_realcost_'+idSubItem).val(total);
    $('#balance_item_realcost_'+idSubItem).val(total);
    $('#budget_variation_pesos_realcost_'+idSubItem).val(total);

    formatCurrency($('#budget_total_realcost_'+idSubItem));
    formatCurrency($('#balance_item_realcost_'+idSubItem));
    formatCurrency($('#budget_variation_pesos_realcost_'+idSubItem));
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

function removeItemRealCost()
{
  $('.removeItemRealCost').click(function() {
    let iditem       = $(this).data('iditem');
    let subItemsContent  = $('.costreal-subitem');

    $('#costreal-item-'+iditem).remove();

    $.each(subItemsContent, function(i, v){
      if($(this).data('iditem') == iditem) {
        $(this).remove();
      }
    });
    
    return false;
  });
}

function validateNumberDecimal()
{
  $(".validate-number-decimal").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
  });
}

function validateTax()
{
  $(".validate-tax").on({
    keyup: function() {
      formatTax($(this));
    },
    blur: function() { 
      formatTax($(this), "blur");
    }
  });
}

function formatNumberTax(n)
{
  try {
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
  } catch (error) {
    console.log(n.val());
  }
}

function formatTax(input, blur)
{
  var input_val = input.val();

  if (input_val === "") { return; }

  var original_len  = input_val.length;
  var caret_pos     = input.prop("selectionStart");
    
  if (input_val.indexOf(".") >= 0)
  {
    var decimal_pos = input_val.indexOf(".");
    var left_side   = input_val.substring(0, decimal_pos);
    var right_side  = input_val.substring(decimal_pos);

    left_side   = formatNumberTax(left_side);
    right_side  = formatNumberTax(right_side);
    
    if (blur === "blur") {
      right_side += "00";
    }
    
    right_side  = right_side.substring(0, 2);
    input_val   = "%" + left_side + "." + right_side;

  } else {
    input_val = formatNumberTax(input_val);
    input_val = "%" + input_val;
    
    if (blur === "blur") { input_val += ".00"; }
  }

  input.val(input_val);

  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

function formatNumber(n)
{
  try {
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  } catch (error) {
    console.log(n.val());
  }
}

function formatCurrency(input, blur)
{
  var input_val = input.val();

  if (input_val === "") { return; }

  var original_len  = input_val.length;
  var caret_pos     = input.prop("selectionStart");
    
  if (input_val.indexOf(".") >= 0)
  {
    var decimal_pos = input_val.indexOf(".");
    var left_side   = input_val.substring(0, decimal_pos);
    var right_side  = input_val.substring(decimal_pos);

    left_side   = formatNumber(left_side);
    right_side  = formatNumber(right_side);
    
    if (blur === "blur") {
      right_side += "00";
    }
    
    right_side  = right_side.substring(0, 2);
    input_val   = "$" + left_side + "." + right_side;

  } else {
    input_val = formatNumber(input_val);
    input_val = "$" + input_val;
    
    if (blur === "blur") { input_val += ".00"; }
  }

  input.val(input_val);

  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

function calculateBudgetGlobalItems()
{
  let itemsContent    = $('.items_content');
  let subItemsContent    = $('.sub_items_content');
  let globalSubtotal  = 0,
      globalTotal     = 0;

  $.each(itemsContent, function(i, v){
    let idItem    = $(this).data('id');
    let subTotal  = $(`input[name=item_subtotal_calc_${idItem}]`).val();
    let total     = $(`input[name=item_total_calc_${idItem}]`).val();

    $(this).find('input[name="item_subtotal"]').val(subTotal);
    $(this).find('input[name="item_total"]').val(total);

    formatCurrency($(this).find('input[name="item_subtotal"]'), 'blur');
    formatCurrency($(this).find('input[name="item_total"]'), 'blur');

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

    formatTax($(this).find('input[name="item_incidence"]'));
  });

  $.each(subItemsContent, function(i, v){
    let idItem      = $(this).data('id');
    let totalGlobal = $('input[name="global_total"]').val();
    let totalItem   = $(`#total_${idItem}`).val();
    let incidence   = ( projectJS.parseFloatPrices(totalItem) / parseFloat(totalGlobal) ) * 100;

    $(`#incidence_${idItem}`).val(incidence.toFixed(2));

    formatTax($(`#incidence_${idItem}`));
  });

  formatCurrency($('input[name="global_subtotal"]'), 'blur');
  formatCurrency($('input[name="global_total"]'), 'blur');
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

    $(this).find('input[name=costreal_budget_total]').val(budgetTotal);
    $(this).find('input[name=costreal_paid]').val(paid);
    $(this).find('input[name=costreal_balance_paid]').val(balancePaid);
    $(this).find('input[name=costreal_balance_item]').val(balanceItem);

    globalbudgetTotal += projectJS.parseFloatPrices(budgetTotal);
    globalpaid        += projectJS.parseFloatPrices(paid);
    globalbalancePaid += projectJS.parseFloatPrices(balancePaid);
    globalbalanceItem += projectJS.parseFloatPrices(balanceItem);
  });

  $('input[name=global_costreal_budgetTotal]').val(globalbudgetTotal);
  $('input[name=global_costreal_paid]').val(globalpaid);
  $('input[name=global_costreal_balancePaid]').val(globalbalancePaid);
  $('input[name=global_costreal_balanceItem]').val(globalbalanceItem);

  formatCurrency($('input[name=global_costreal_budgetTotal]'));
  formatCurrency($('input[name=global_costreal_paid]'));
  formatCurrency($('input[name=global_costreal_balancePaid]'));
  formatCurrency($('input[name=global_costreal_balanceItem]'));
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

function addNewBudget(e)
{
    e.preventDefault();

    if($('input[name=name_budget]').val())
    {
      
      $(".budget-event-hidden").addClass('hidden');
      $(".budget-event-show").removeClass('hidden');

      $('input[name=name_budget]').parent('div').removeClass('col-md-5').addClass('col-md-11');

      // add all items and subitems
      $.each($("select[name=items] option"), function(){
          let idItem      = $(this).val();
          let numberItem  = $(this).data('number');
          let nameItem    = $(this).text();

          let dataSubItems  = [];
          $("#select-subitems-"+idItem+" select[name=sub_items] option").each(function(){
              dataSubItems.push({
                name: $(this).text(),
                number: $(this).data('number'),
                id: $(this).val()
              });
          });
    
          generateTemplateItems(idItem, numberItem, nameItem, dataSubItems);
      });
      
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

    } else if(type == "egress") {

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

    } else if(type == "direct_payment") {
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
        id_provider   : "0",
        clarification : $('input[name=cash_flow_clarification]').val(),
        id_user       : $('select[name=cash_flow_id_user]').find(':selected').val(),
        id_client     : "0"
      }
    }

    if(validateObjectValues(data))
    {
      let balanceCashFlow = parseFloat($('input[name=balance-cash-flow]').val());
      let amountCashFlow  = parseFloat(data.amount.toString().replace(/\./g, "").replace(",", "."));

      if(type == "egress" && balanceCashFlow < amountCashFlow)
      {
        Swal.fire({
          title: 'El Monto a registrar supera el Saldo de Caja!',
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar'
        })
      } else {
        addCashFlowAjax(data);
      }
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

function addCashFlowAjax(data)
{
  $.ajax({
    type: 'POST',
    url: '/project_cash_flow',
    data: data,
    success: function(result){
      if(result.status){
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

  getProvidersFromItem: (item, selectProviders, toggleSubItem) => {

    let providers = $(`select[name=${selectProviders}]`);
    let selectSubItem = $(`select[name=${toggleSubItem}]`);
    let selectSubItemCollection = $(`.cash_flow_id_subitem_collection`).children('optgroup');

    selectSubItem.empty();
    
    selectSubItem.append("<option value=''>Seleccionar</option>");
    $.each(selectSubItemCollection, function() {
      if($(this).data('id') == $(item).val())
      {
        selectSubItem.append($(this).html());
      }
    });

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
          $('.selectpicker').selectpicker('refresh');
  
          Swal.fire({
            title: 'No hay Proveedores para el Rubro seleccionado!',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
          });
        }
      },
      error: function(xhr, status){
        providers.empty();
        $('.selectpicker').selectpicker('refresh');

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
  },

  toggleFieldRequired: (ty) => {
    let type  = $(ty).find(':selected').val();

    if(type == "entry")
    {      
      $('.egress-required').addClass('hidden');
      $('.direct_payment-required').addClass('hidden');
    } else if(type == "egress") {
      $('.egress-required').removeClass('hidden');
    } else if(type == "direct_payment") {
      $('.egress-required').addClass('hidden');
      $('.direct_payment-required').removeClass('hidden');
    }

    $('.default-required').removeClass('hidden');

  },

  parseFloatPrices: (price) =>  {
      return (price ? parseFloat(price.replace("$", "").replace(/\,/g, "").replace("%", "")) : 0);
  },

  validateNumberDecimalAddAttr: (className = 'validate-number-decimal') => {
    // add attributes 
    $("."+className).attr('pattern', "^\$\d{1,3}(,\d{3})*(\.\d+)?$");
  }

};

