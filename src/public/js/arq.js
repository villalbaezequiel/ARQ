function addActiveNavSidebar(idItemNav, idContentItemNav) {

  var urlPathname = window.location.pathname;
  if(urlPathname === idItemNav || urlPathname === idItemNav+"/create"){

    $("#"+idItemNav+"-nav").addClass('active');
    $("#exampleAccordion").animate({ scrollTop: $(document).height() }, 1000);
    $(".nav-link-collapse").removeClass('collapsed');
    $(".nav-link-collapse").attr('aria-expanded', 'true');
    $("#"+idContentItemNav).addClass('show');
  }
}

function alertLeaveForm(link){
  // se genera un alert de confirmacion para abandonar el Formulario
  Swal.fire({
    title: '¿Estás seguro que quieres abandonar el Formulario?',
    text: "Tener en cuenta que los campos completados no se guardarán!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, continuar!'
  })
  .then((result) => {
    if(result.dismiss == "cancel"){
      return false;
    }
    if(result.value == true){
      if($(link).hasClass('back-history')){
        window.history.go(-1); return false;
      } else {
        window.location.href = $(link).attr('href');
      }
    }
  })
}

function alertConfirmDeleteItem(form){
  // se genera un alert de confirmacion para abandonar el Formulario
  Swal.fire({
    title: '¿Estás seguro que quieres Eliminar el Elemento Seleccionado?',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, continuar!'
  })
  .then((result) => {
    if(result.dismiss == "cancel"){
      return false;
    }
    if(result.value == true){

      $.ajax({
        url: $(form).attr('action'),
        data: {_method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content')},
			  type: 'POST'
      })
      .done(function(data) {
  			$(form).parents('.item-alert-confirm-delete').remove();
        Swal.fire(
          data.message,
          '',
          'success'
        );
      })
      .fail(function(xhr) {
        console.log(xhr);
			});

    }
  })
}

function openWindowUrl(url, width) {
  window.open(url, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width="+width+", height=900");
}

function disableEnterKey(e){
  var key;
  if(window.event){
    key = window.event.keyCode; //IE
  }else{
    key = e.which; //firefox
  }
  if(key==13){
    return false;
  }else{
    return true;
  }
}

$(document).ready(function(){

  // datatables global
  $("#datatables").DataTable({
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    },
    responsive: true,
    aaSorting: []
  });

  // Nav Check url active menu
  var url = window.location.href;
  $(".nav-check-url").each(function(i,v){
    var link = $(this).children('a').attr('href');
    if(url === link || url === link+"/create"){
      $(this).addClass('active');
    }
  });
  let todayDatepicker = new Date();

  if($(".datepicker").hasClass('validate-year'))
  {
    todayDatepicker = `${todayDatepicker.getDate()}/${todayDatepicker.getMonth()+1}/${todayDatepicker.getFullYear()-70}`;
  } else {
    todayDatepicker = `${todayDatepicker.getDate()}/${todayDatepicker.getMonth()+1}/${todayDatepicker.getFullYear()}`;
  }

  // jQuery UI -> Datepicker
  $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '< Ant',
    nextText: 'Sig >',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    defaultDate: todayDatepicker,
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: '',
    changeYear: true,
    yearRange: "-60:+60",
  };
  $.datepicker.setDefaults($.datepicker.regional['es']);

  $(".datepicker").datepicker({
    onSelect: function(){
      var days = 0;
      var startDay = $("input[name=start_date]").datepicker('getDate');
      var endDay = $("input[name=finish_date]").datepicker('getDate');

      if (startDay.length > 0 && endDay.length > 0) {
        days = Math.floor((endDay.getTime() - startDay.getTime()) / 86400000); // ms per day
      }

      $("input[name=days]").val((days+1));

      if($(this).hasClass('validate-year'))
      {
        let years = new Date(new Date() - new Date($(this).val())).getFullYear() - 1970;

        if(years < 18) {
            // $(this).val('');
            // alert("Tienes que ser mayor a 18 años");
        };
      }
    }
  });

  $(".validate-number-decimal").on({
    "focus": function(event) {
      $(event.target).select();
    },
    "keyup": function(event) {
      $(event.target).val(function(index, value) {
        return "$"+(value.replace(/\D/g, "")
          .replace(/([0-9])([0-9]{2})$/, '$1,$2')
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
      });
    }
  });

  $(".validate-number-decimal-not-ps").on({
    "focus": function(event) {
      $(event.target).select();
    },
    "keyup": function(event) {
      $(event.target).val(function(index, value) {
        return (value.replace(/\D/g, "")
          .replace(/([0-9])([0-9]{2})$/, '$1,$2')
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, "."));
      });
    }
  });

  // validate input number for class input-type-number
  $(".input-type-number").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

});


let arqJs = {

  initDateTimePicker: () => {
    $('.datetimepicker').datetimepicker({
        icons: {
          time: "fa fa-clock-o",
          date: "fa fa-calendar",
          up: "fa fa-chevron-up",
          down: "fa fa-chevron-down",
          previous: 'fa fa-chevron-left',
          next: 'fa fa-chevron-right',
          today: 'fa fa-screenshot',
          clear: 'fa fa-trash',
          close: 'fa fa-remove'
        }
     });
  },

  initMaterialWizard: (rules) => {
   // Code for the Validator
   var $validator = $('.card-wizard form').validate({
     rules: rules,

     highlight: function(element) {
         $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
     },
     success: function(element) {
         $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
     },
     errorPlacement : function(error, element) {
         $(element).append(error);
     }
   });

     // Wizard Initialization
     $('.card-wizard').bootstrapWizard({
         'tabClass': 'nav nav-pills',
         'nextSelector': '.btn-next',
         'previousSelector': '.btn-previous',

         onNext: function(tab, navigation, index) {
             var $valid = $('.card-wizard form').valid();
             if (!$valid) {
                 $validator.focusInvalid();
                 return false;
             }
         },

         onInit: function(tab, navigation, index) {
             //check number of tabs and fill the entire row
             var $total = navigation.find('li').length;
             var $wizard = navigation.closest('.card-wizard');

             $first_li = navigation.find('li:first-child a').html();
             $moving_div = $('<div class="moving-tab">' + $first_li + '</div>');
             $('.card-wizard .wizard-navigation').append($moving_div);

             refreshAnimation($wizard, index);

             $('.moving-tab').css('transition', 'transform 0s');
         },

         onTabClick: function(tab, navigation, index) {
             var $valid = $('.card-wizard form').valid();

             if (!$valid) {
                 return false;
             } else {
                 return true;
             }
         },

         onTabShow: function(tab, navigation, index) {
             var $total = navigation.find('li').length;
             var $current = index + 1;

             var $wizard = navigation.closest('.card-wizard');

             // If it's the last tab then hide the last button and show the finish instead
             if ($current >= $total) {
                 $($wizard).find('.btn-next').hide();
                 $($wizard).find('.btn-finish').show();
             } else {
                 $($wizard).find('.btn-next').show();
                 $($wizard).find('.btn-finish').hide();
             }

             button_text = navigation.find('li:nth-child(' + $current + ') a').html();

             setTimeout(function() {
                 $('.moving-tab').text(button_text);
             }, 150);

             var checkbox = $('.footer-checkbox');

             if (!index == 0) {
                 $(checkbox).css({
                     'opacity': '0',
                     'visibility': 'hidden',
                     'position': 'absolute'
                 });
             } else {
                 $(checkbox).css({
                     'opacity': '1',
                     'visibility': 'visible'
                 });
             }

             refreshAnimation($wizard, index);
         }
     });


     // Prepare the preview for profile picture
     $("#wizard-picture").change(function() {
         readURL(this);
     });

     $('[data-toggle="wizard-radio"]').click(function() {
         wizard = $(this).closest('.card-wizard');
         wizard.find('[data-toggle="wizard-radio"]').removeClass('active');
         $(this).addClass('active');
         $(wizard).find('[type="radio"]').removeAttr('checked');
         $(this).find('[type="radio"]').attr('checked', 'true');
     });

     $('[data-toggle="wizard-checkbox"]').click(function() {
         if ($(this).hasClass('active')) {
             $(this).removeClass('active');
             $(this).find('[type="checkbox"]').removeAttr('checked');
         } else {
             $(this).addClass('active');
             $(this).find('[type="checkbox"]').attr('checked', 'true');
         }
     });

     $('.set-full-height').css('height', 'auto');

     //Function to show image before upload

     function readURL(input) {
         if (input.files && input.files[0]) {
             var reader = new FileReader();

             reader.onload = function(e) {
                 $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
             }
             reader.readAsDataURL(input.files[0]);
         }
     }

     $(window).resize(function() {
         $('.card-wizard').each(function() {
             $wizard = $(this);

             index = $wizard.bootstrapWizard('currentIndex');
             refreshAnimation($wizard, index);

             $('.moving-tab').css({
                 'transition': 'transform 0s'
             });
         });
     });

     function refreshAnimation($wizard, index) {
         $total = $wizard.find('.nav li').length;
         $li_width = 100 / $total;

         total_steps = $wizard.find('.nav li').length;
         move_distance = $wizard.width() / total_steps;
         index_temp = index;
         vertical_level = 0;

         mobile_device = $(document).width() < 600 && $total > 3;

         if (mobile_device) {
             move_distance = $wizard.width() / 2;
             index_temp = index % 2;
             $li_width = 50;
         }

         $wizard.find('.nav li').css('width', $li_width + '%');

         step_width = move_distance;
         move_distance = move_distance * index_temp;

         $current = index + 1;

         if ($current == 1 || (mobile_device == true && (index % 2 == 0))) {
             move_distance -= 8;
         } else if ($current == total_steps || (mobile_device == true && (index % 2 == 1))) {
             move_distance += 8;
         }

         if (mobile_device) {
             vertical_level = parseInt(index / 2);
             vertical_level = vertical_level * 38;
         }

         $wizard.find('.moving-tab').css('width', step_width);
         $('.moving-tab').css({
             'transform': 'translate3d(' + move_distance + 'px, ' + vertical_level + 'px, 0)',
             'transition': 'all 0.5s cubic-bezier(0.29, 1.42, 0.79, 1)'

         });
     }
  },

    getChangeOs: (v) => {
      let contentSocialWorkC = document.getElementById('social_work_code_content');
      let social_work_name_content = document.getElementById('social_work_name_content');
      let inputSocialWorkCode = document.getElementsByName('social_work_code');

      if(v == "1"){
        contentSocialWorkC.classList.remove("hidden");
        social_work_name_content.classList.remove("hidden");
      } else {
        contentSocialWorkC.classList.add("hidden");
        social_work_name_content.classList.add("hidden");
        inputSocialWorkCode.value = '';
      }
    },

    getRandomColor: () => {
      var letters = '0123456789ABCDEF';
      var color = '#';
      for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    },

    getTimeCalendar: () => {
      const schedules = [
        "00:00","00:30",
        "01:00","01:30",
        "02:00","02:30",
        "03:00","03:30",
        "04:00","04:30",
        "05:00","05:30",
        "06:00","06:30",
        "07:00","07:30",
        "08:00","08:30",
        "09:00","09:30",
        "10:00","10:30",
        "11:00","11:30",
        "12:00","12:30",
        "13:00","13:30",
        "14:00","14:30",
        "15:00","15:30",
        "16:00","16:30",
        "17:00","17:30",
        "18:00","18:30",
        "19:00","19:30",
        "20:00","20:30",
        "21:00","21:30",
        "22:00","22:30",
        "23:00","23:30"
      ];

      return schedules;
    },

    setDisabled: (row) => {
      $(row).prop('disabled', true);
    },

    updateList: () => {
      var input = document.getElementById('file_id');
      var output = document.getElementById('name_files_list');
      var children = "";
      for (var i = 0; i < input.files.length; ++i) {
          children += "<li class='list-group-item'>" + input.files.item(i).name + '</li>';
      }

      if(children.length > 0)
      {
        $("#name_files_content").removeClass("hidden");
        output.innerHTML = "<ul class='list-group'>"+children+'</ul>';
      }
    },

    getSubItemsFromItems: (items, nameSelectSubItems) => {

        var subItems = $(`#${nameSelectSubItems}`);

        if(!$(items).val()) {
          subItems.empty();
          return false;
        }

        $.ajax({
          type: 'GET',
          url: '/json/items/sub_items/',
          dataType: 'json',
          data: { items: $(items).val() },
          success: function(result){
            
            let data = result.data;
              
            subItems.empty();

            if(!$.isEmptyObject(data))
            {
                $.each(data, function(i, v)
                {
                  var idOptgroup  = i.normalize('NFD').replace(/[\u0300-\u036f]/g,"");
                  idOptgroup      = idOptgroup.replace(/ /g, '_'); 

                  subItems.append(`<optgroup label="${i}" id="${idOptgroup}" data-min-options="1"></optgroup>`);
                  
                  if(v.length > 0) {
                    $.each(v, function(){
                      $(`#${idOptgroup}`).append(`<option value='${this.id}'>${this.name}</option>`);
                    });
                  }
                });

                $('.selectpicker').selectpicker('refresh');

            } else {
              Swal.fire({
                title: 'No hay Sub Rubros para el Rubro seleccionado!',
                type: 'info',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
              });
            }
          },
          error: function(xhr, status){
            Swal.fire({
              title: 'Hubo un error al Consultar los Sub Rubros!',
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Aceptar'
            });
          }
        });

        $('.selectpicker').selectpicker('refresh');
    }

}
