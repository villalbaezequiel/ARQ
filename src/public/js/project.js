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


    $(".calculate-subtotal").keyup(function(){
      let id = $(this).data('idsubiteam');

      let valCalculate = ( $('#quantity_'+id).val() * $('#unit_cost_'+id).val());

      $('#subtotal_'+id).val(valCalculate);
      $('#total_'+id).val(valCalculate);
    });
    
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
        if($(this).data('iditeam') == idItem) {
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
      <th> </th>
      <th> </th>
      <th> </th>
      <th> </th>
      <th>
        <button
          class="btn btn-danger removeItem" 
          data-iditeam="${idItem}" 
          style="padding: 0px 5px;"
        >X</button>
      </th>
    </tr>`;

  let realCost = `
    <tr class="b-c-gray">
      <th colspan="4" class="text-left pl-3">${nameItem}</th>
      <th> </th>
      <th> </th>
      <th> </th>
      <th> </th>
    </tr>`;

  $.each(dataSubItems, function(i, v){
    items += `
      <tr id="subitem-${v.id}" data-iditeam="${idItem}" class="sub_items_content">
        <td class="pl-3"><input class="form-control" value="${v.name}" disabled /></td>
        <td><input type="text" name="unit[${v.id}][]" class="form-control" /></td>
        <td><input type="number" name="quantity[${v.id}][]" id="quantity_${v.id}" data-idsubiteam="${v.id}" class="form-control calculate-subtotal" /></td>
        <td><input type="number" name="unit_cost[${v.id}][]" id="unit_cost_${v.id}" data-idsubiteam="${v.id}" class="form-control calculate-subtotal" /></td>
        <td><input type="number" name="subtotal[${v.id}][]" id="subtotal_${v.id}" class="form-control" readonly /></td>
        <td><input type="number" name="tax[${v.id}][]" class="form-control" /></td>
        <td><input type="number" name="total[${v.id}][]" id="total_${v.id}" class="form-control" readonly /></td>
        <td><input type="number" name="incidence[${v.id}][]" id="incidence_${v.id}" class="form-control" readonly /></td>
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

    realCost += `
      <tr>
        <td class="pl-3"><input class="form-control" value="${v.name}" disabled /></td>
        <td><input type="number" name="unit[${v.id}][]" class="form-control" /></td>
        <td><input type="number" name="quantity[${v.id}][]" class="form-control" /></td>
        <td><input type="number" name="unit_cost[${v.id}][]" class="form-control" readonly /></td>
        <td><input type="number" name="subtotal[${v.id}][]" class="form-control" readonly /></td>
        <td><input type="number" name="tax[${v.id}][]" class="form-control" readonly /></td>
        <td><input type="number" name="total[${v.id}][]" class="form-control" readonly /></td>
        <td><input type="number" name="total[${v.id}][]" class="form-control" readonly /></td>
      </tr>
    `;
  });
  
  $('#content-items tbody').append(items);
  $('#content-real-cost tbody').append(realCost);

  calculateSubTotal();
  removeItem();
}

function setSelectedOptions()
{
  $('select[name=items] option:selected').prop("selected", false);
  $('select[name=sub_items] option:selected').prop("selected", false);
  $('.selectpicker').selectpicker('refresh');
}

function calculateSubTotal(){
  $(".calculate-subtotal").keyup(function(){
    let id = $(this).data('idsubiteam');

    let valCalculate = ( $('#quantity_'+id).val() * $('#unit_cost_'+id).val());

    $('#subtotal_'+id).val(valCalculate);
    $('#total_'+id).val(valCalculate);
  });
}

function calculateSubTotal(){
  $(".calculate-subtotal").keyup(function(){
    let id = $(this).data('idsubiteam');

    let valCalculate = ( $('#quantity_'+id).val() * $('#unit_cost_'+id).val());

    $('#subtotal_'+id).val(valCalculate);
    $('#total_'+id).val(valCalculate);
  });
}

function removeItem()
{
  $('.removeItem').click(function() {
    let iditeam       = $(this).data('iditeam');
    let subItemsContent  = $('.sub_items_content');

    $('#item-'+iditeam).remove();

    $.each(subItemsContent, function(i, v){
      if($(this).data('iditeam') == iditeam) {
        $(this).remove();
      }
    });
    
    return false;
  });
}