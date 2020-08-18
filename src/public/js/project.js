$(document).ready(function() {

    let showURL = window.location.pathname;

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

    $('.save-continue').mouseover(function(){
        let status = true;

        if( $('.tab-pane.active').attr('id') == 'module_five' ||
            $('.tab-pane.active').attr('id') == 'module_six' ||
            $('.tab-pane.active').attr('id') == 'module_four')
        {
          status = false;
        }

        if($('.tab-pane.active').attr('id') == 'module_two' && $('input[name=open_budget]').val() == 0) status = false; 

        if(status)
        {
          let dataLogicProject = $('.tab-pane.active :input').serialize();

          $.ajax({
            type: 'PUT',
            url: '/projects/'+$('#logic_project').data('id'),
            dataType: 'json',
            data: dataLogicProject,
            success: function(response){
              if(!response.status){
                Swal.fire({
                  title: 'Error al Guardar!',
                  type: 'warning',
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
        }
    });

    $('.save-continue').click(function(){

      $('.spinner').show();
      $("#content-data-budget tr").remove();
      $("#content-data-realcost tr").remove();

      setTimeout(function(){
        
        if($('.tab-pane.active').attr('id') == 'module_two') processDataBudget();

        if($('.tab-pane.active').attr('id') == 'module_three') processDataRealCost();

        if($('.tab-pane.active').attr('id') != 'module_two' || $('.tab-pane.active').attr('id') == 'module_three')
        {
          setTimeout(function(){ $('.spinner').hide(); }, 150);
        }

      }, 100);
      
    });

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

        $(".spinner").show();

        $('textarea[name=observations]').val(result.value);

        if(showURL == "/projects/create")
        {
          let dataLogicProject = $('#logic_project').serialize();

          $.ajax({
            type: 'POST',
            url: '/projects',
            data: dataLogicProject ,
            success: function(response){
              if(response.status){
                projectJS.setUrlPath('/projects');
              }
            },
            error: function(xhr, status){
              console.log('Error: '+xhr);
            }
          });
        } else {
          let dataLogicProject = $('.tab-pane.active :input').serialize();

          $.ajax({
            type: 'PUT',
            url: '/projects/'+$('#logic_project').data('id'),
            dataType: 'json',
            data: dataLogicProject,
            success: function(response){
              if(response.status){
                projectJS.setUrlPath('/projects');
              }
            },
            error: function(xhr, status){
              console.log('Error: '+xhr);
            }
          });
        }
      
      });
    });

    $('.select-items-realcost').change(function(){
      let idItem = $(this).find(':selected').val();
      $('.select-subitems-realcost').addClass('hidden');
      $(`#select-subitems-${idItem}`).removeClass('hidden');
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
    projectJS.resetValueDefault();
    projectJS.disabledCLick();

});

function getAlertInfo(message)
{
  Swal.fire({
    title: message,
    type: 'info',
    showCancelButton: false,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Aceptar'
  });
}

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
      projectJS.scrollPosition(`item-${idItem}`);

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

    let dataSubItems  = []
    let issetItem     = false;
    let issetSubItem  = false;

    let idItem      = $("select[name=items]").find(':selected').val();
    let nameItem    = $("select[name=items]").find(':selected').text();
    let numberItem  = $("select[name=items]").find(':selected').data('number');

    let idSubItem      = $("#select-subitems-"+idItem+" select[name=sub_items]").find(':selected').val();
    let nameSubItem    = $("#select-subitems-"+idItem+" select[name=sub_items]").find(':selected').text();
    let numberSubItem  = $("#select-subitems-"+idItem+" select[name=sub_items]").find(':selected').data('number');

    if($("#costreal-subitem-"+idSubItem).length > 0)
    {
      issetSubItem = true; 
    } else {
      dataSubItems.push({
        name: nameSubItem,
        number: numberSubItem,
        id: idSubItem
      });
    } 

    if($("#costreal-item-"+idItem).length > 0)
    {
      issetItem = true;  
    }

    if(issetSubItem)
    {
      Swal.fire({
        title: 'Sub Rubro Existente en la Planilla!',
        type: 'info',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
      });
      return false;
    }

    generateTemplateItemsRealCost(issetItem, idItem, numberItem, nameItem, dataSubItems);
    setSelectedOptions();

    // add all subitems
    /*let dataSubItems  = [];
    $("#select-subitems-"+idItem+" select[name=sub_items] option").each(function(){
        dataSubItems.push({
          name: $(this).text(),
          number: $(this).data('number'),
          id: $(this).val()
        });
    });*/ 
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
        <td><input type="text"    name="budget[${v.id}][unit]"      value="U" class="form-control text-center" required /></td>
        <td><input type="number"  name="budget[${v.id}][quantity]"  maxlength="11"  id="quantity_${v.id}" value="1" data-idsubiteam="${v.id}" class="form-control text-center calculate-subtotal" required /></td>
        <td><input type="text"    name="budget[${v.id}][unit_cost]" maxlength="14"  id="unit_cost_${v.id}" data-idsubiteam="${v.id}" value="$1" class="form-control text-center calculate-subtotal validate-number-decimal reset-value" required /></td>
        <td><input type="text"    name="budget[${v.id}][subtotal]"  maxlength="14"  id="subtotal_${v.id}"  value="$1" class="form-control text-center validate-number-decimal" readonly required /></td>
        <td><input type="text"    name="budget[${v.id}][tax]"       maxlength="6"   id="tax_${v.id}" value="%0" data-idsubiteam="${v.id}" class="form-control text-center validate-tax calculate-subtotal reset-value" /></td>
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
  projectJS.resetValueDefault();
}

function generateTemplateItemsRealCost(issetItem = false, idItem, numberItem, nameItem, dataSubItems)
{
  let subItems = '';
  if(!issetItem)
  {
    $("#content-real-cost tbody").append(`
    <tr class="b-c-gray" id="costreal-item-${idItem}" data-id="${idItem}">
      <th>${numberItem}</th>
      <th colspan="4" class="text-left pl-3">${nameItem}</th>
      <th><input type="text" name="costreal_budget_total"   class="form-control" disabled></th>
      <th><input type="text" name="costreal_paid"           class="form-control" disabled></th>
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
    </tr>
    <input type="hidden" id="add-subitems-item-${idItem}">
    `);
  }

  let scrollID = '';

  $.each(dataSubItems, function(i, v){
    
    scrollID = `costreal-subitem-${v.id}`;

    subItems += `
      <tr id="costreal-subitem-${v.id}" data-iditem="${idItem}" class="costreal-subitem">
      
        <input      type="hidden" name="real_cost[${v.id}][id_item]" value="${idItem}" />

        <td>${v.number}</td>
        <td class="pl-3"><input class="form-control" value="${v.name}" disabled /></td>
        <td><input type="number"name="real_cost[${v.id}][quantity]" value="1"      id="quantity_realcost_${v.id}" maxlength="11" data-idsubiteam="${v.id}"  class="form-control text-center calculate-subtotal-realcost" /></td>
        <td><input type="text"  name="real_cost[${v.id}][unit_cost]" value="$1"    id="unit_cost_realcost_${v.id}"  maxlength="14" data-idsubiteam="${v.id}" class="form-control text-center validate-number-decimal calculate-subtotal-realcost reset-value" /></td>
        <td><input type="text"  name="real_cost[${v.id}][tax]" value="%0"           id="tax_realcost_${v.id}"  maxlength="6" data-idsubiteam="${v.id}" class="form-control text-center validate-tax calculate-subtotal-realcost reset-value" /></td>
        <td><input type="text"  name="real_cost[${v.id}][budget_total]" value="$1" id="budget_total_realcost_${v.id}"  maxlength="14"  class="form-control text-center validate-number-decimal" readonly /></td>
        <td><input type="text"  name="real_cost[${v.id}][paid]" maxlength="14"  class="form-control text-center validate-number-decimal" readonly /></td>
        <td><input type="text"  name="real_cost[${v.id}][balance_item]" maxlength="14" id="balance_item_realcost_${v.id}" class="form-control text-center validate-number-decimal" readonly /></td>
        <td><input type="text"  name="real_cost[${v.id}][budget_variation_pesos]" id="budget_variation_pesos_realcost_${v.id}" maxlength="14"  class="form-control text-center validate-number-decimal" readonly /></td>
        <td><input type="text"  name="real_cost[${v.id}][budget_variation_tax]" maxlength="6"   class="form-control text-center validate-tax" readonly /></td>

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
  
  if(!issetItem){
    $("#content-real-cost tbody").append(subItems);
  } else {
    $(`#add-subitems-item-${idItem}`).before(subItems);
  }

  calculateTotalesRealCost();
  removeItemRealCost();
  validateNumberDecimal();
  validateTax();
  projectJS.validateNumberDecimalAddAttr();
  projectJS.scrollPosition(scrollID);
  projectJS.resetValueDefault();
}

function setSelectedOptions()
{
  $('select[name=items] option:selected').prop("selected", false);
  $('select[name=sub_items] option:selected').prop("selected", false);

  $('.select-subitems').addClass('hidden');
  $('.select-subitems').first().removeClass('hidden');

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

    formatCurrency($('#budget_total_realcost_'+idSubItem), 'blur');
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

  if(input.val().length == 7){
    input.attr('maxlength', '7');
  }

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
      globalbalanceItem = 0;

  $.each(itemsContent, function(i, v){
    let idItem        = $(this).data('id');
    let budgetTotal   = $(`input[name=costreal_budget_total_calc_${idItem}]`).val();
    let paid          = $(`input[name=costreal_paid_calc_${idItem}]`).val();
    let balanceItem   = $(`input[name=costreal_balance_item_calc_${idItem}]`).val();

    $(this).find('input[name=costreal_budget_total]').val(budgetTotal);
    $(this).find('input[name=costreal_paid]').val(paid);
    $(this).find('input[name=costreal_balance_item]').val(balanceItem);

    globalbudgetTotal += projectJS.parseFloatPrices(budgetTotal);
    globalpaid        += projectJS.parseFloatPrices(paid);
    globalbalanceItem += projectJS.parseFloatPrices(balanceItem);
  });

  $('input[name=global_costreal_budgetTotal]').val(globalbudgetTotal);
  $('input[name=global_costreal_paid]').val(globalpaid);
  $('input[name=global_costreal_balanceItem]').val(globalbalanceItem);

  formatCurrency($('input[name=global_costreal_budgetTotal]'), 'blur');
  formatCurrency($('input[name=global_costreal_paid]'), 'blur');
  formatCurrency($('input[name=global_costreal_balanceItem]'), 'blur');
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
  $('.removeCostRealItemAjax').click(function(e){

    e.preventDefault();

    let iditem            = $(this).data('iditem');
    let costrealSubitem   = $('.costreal-subitem');
    let checkCostrealPaid = projectJS.parseFloatPrices($("#costreal-item-"+iditem+" input[name=costreal_paid]").val());

    if(checkCostrealPaid != 0)
    {
      Swal.fire({
        title: 'No se puede eliminar el Rubro Seleccionado. Dado que existen Sub-Rubros con Flujo de Caja Ingresados!',
        type: 'info',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
      });

      return false;
    }

    Swal.fire({
      title: '¿Estas seguro que desea eliminar el Rubro?',
      type: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar'
    })
    .then((result) => {

      if(result.dismiss == "cancel") { return false; }

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

            $(`input[name=costreal_budget_total_calc_${iditem}]`).remove();
            $(`input[name=costreal_paid_calc_${iditem}]`).remove();
            $(`input[name=costreal_balance_item_calc_${iditem}]`).remove();
  
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

  });
}

function deleteCostRealSubItem()
{
  $('.removeCostRealSubItemAjax').click(function(e){

    e.preventDefault();
    
    let checkCostrealPaidSubItem = projectJS.parseFloatPrices($('#costreal-subitem-'+$(this).data('subitemid')+' .costreal-subitem-paid').val());

    if(checkCostrealPaidSubItem != 0)
    {
      Swal.fire({
        title: 'No se puede eliminar el Sub-Rubro Seleccionado. Dado que tiene Ingresado Flujo de Caja!',
        type: 'info',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
      });

      return false;
    }

    Swal.fire({
      title: '¿Estas seguro que desea eliminar el Sub-Rubro?',
      type: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar'
    })
    .then((result) => {

      if(result.dismiss == "cancel") { return false; }

      $('#costreal-subitem-'+$(this).data('subitemid')).remove();

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

      projectJS.activeSave();
      
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

    let cashFlowClarification = $('input[name=cash_flow_clarification]').val();

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
          clarification : (cashFlowClarification ? cashFlowClarification : " "),
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
        clarification : (cashFlowClarification ? cashFlowClarification : " "),
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
        clarification : (cashFlowClarification ? cashFlowClarification : " "),
        id_user       : $('select[name=cash_flow_id_user]').find(':selected').val(),
        id_client     : "0"
      }
    }

    if(validateObjectValues(data))
    {
      let balanceCashFlow = parseFloat($('input[name=balance-cash-flow]').val());
      let amountCashFlow  = parseFloat(data.amount.replace("$", "").replace(/\,/g, ""));

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
                      <form action="/export/project_provider" class="export-history-provider" target="_blank">
                        <input type="hidden" name="id_project" value="">
                        <input type="hidden" name="id_provider" value="">
                        <input type="submit" class="btn btn-primary" value="Descargar Historial Proveedor">
                      </form>
                    </div>
                  </div>
                  `;

  return formSwal;
}

function getTemplateClients(){
  let formSwal = `<div class="row">
        <label class="col-sm-4 text-right pt-4">Clientes:</label>
        <div class="col-sm-6">
          <div class="form-group">
            <select class="form-control selectpicker" data-style="btn btn-link" name="realcost_client" required>
            </select>
          </div>
        </div>
      </div>`;

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
                });
  
                $(".export-history-provider input[name=id_project]").val(idProject);
                $(".export-history-provider input[name=id_provider]").val(idProvider);
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
  },

  resetValueDefault: () => {
    $('.reset-value').click(function(){
      if( $(this).val() == "$1" || $(this).val() == "$1.00" ||
          $(this).val() == "%0" || $(this).val() == "%0.00")
      {
        $(this).val('');
      } 
    });
  },

  activeSave: () => {
      $('.btn-finish').show();
  },

  showSpinner: () => {
    $('.spinner').show();
  },

  hideSpinner: () => {
    $('.spinner').hide();
  },

  setUrlPath: (path) => {
    window.location.replace(window.location.origin + path);
  },

  disabledCLick: () => {
      $('.disabled-click').click(false);
  },

  scrollPosition: (idElement) => {
    $('html, body').animate({
        scrollTop: $("#"+idElement).offset().top
    }, 2000);
  },

  sendRealCostMailClient: (e) => {
    e.preventDefault();

    console.log();
    
    Swal.fire({
      title: `Elegir Cliente para enviar Seguimiento`,
      html: getTemplateClients(),
      confirmButtonText: 'Enviar',
      confirmButtonClass: "btn btn-success",
      showCancelButton: false,
      customClass: 'swal-wide',
      buttonsStyling: !1,
      onBeforeOpen: () => {

        $('select[name=realcost_client]').append(`<option value="">Seleccionar</option>`);
        
        $.each(collectionClients, function(i, v){
          $('select[name=realcost_client]').append(`<option value="${v.id}">${v.last_name} ${v.name}</option>`);
        });

        $('.selectpicker').selectpicker('refresh');
      }
    })
    .then(function(data){
      if(data.value){

        $('.spinner').show();

        let client    = $("select[name=realcost_client]").find(':selected').val();
        let idProject = $('#logic_project').data('id');
  
        if(client)
        {
          $.ajax({
            type: 'POST',
            url: '/json/send-email-realcost',
            data: {client: client, project: idProject},
            success: function(data){

              setTimeout(function(){ $('.spinner').hide(); }, 50);
              
              if(data.status)
              {
                getAlertInfo("Seguimiento enviado con Exito!");
              } else {
                getAlertInfo("Se ha generado un Error!");
              }

            },
            error: function(xhr, status){
              getAlertInfo("Se ha generado un Error!");
              console.log('Error: '+xhr);
            }
          });
  
        } else {
          getAlertInfo("Para Enviar el Seguimiento debes seleccionar un Cliente! Gracias.");
        }
  
      }
    });   
  }

};