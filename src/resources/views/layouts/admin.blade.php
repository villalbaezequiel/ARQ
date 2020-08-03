<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>ARQ Obras - @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/svg+xml" href="" />
    <link rel="icon" type="image/png" href="" />
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('css/material-dashboard.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />

    <style>
      .sidebar-background {
        background-size: auto!important;
      }
    </style>
</head>

<body class="">
    <div class="wrapper">
      @include('backend.components.sidebar')
      <div class="main-panel">
        @include('backend.components.nav')

        <div class="content">
          @yield('content')

          <div class="spinner" style="display: none;"></div>

        </div>
        @include('backend.components.footer')
      </div>
    </div>
    
    @guest
    @else
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>
    @endif

    <!-- Javascripts Plugins  -->
    <script src="{{ asset('/js/core/jquery-3.3.1.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="{{ asset('/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('/js/core/bootstrap-material-design.min.js') }}"></script>
    <!-- Plugin for the momentJs  -->
    <script src="{{ asset('/js/plugins/moment.min.js') }}"></script>
    <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
    <script src="{{ asset('/js/plugins/fullcalendar.min.js') }}"></script>
    <!--  Plugin for Sweet Alert -->
    <script src="{{ asset('/js/plugins/sweetalert2.js') }}"></script>
    <!-- Forms Validations Plugin -->
    <script src="{{ asset('/js/plugins/jquery.validate.min.js') }}"></script>
    <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
    <script src="{{ asset('/js/plugins/jquery.bootstrap-wizard.js') }}"></script>
    <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
    <script src="{{ asset('/js/plugins/bootstrap-selectpicker.js') }}"></script>

    <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="{{ asset('/js/plugins/jquery.dataTables.min.js') }}"></script>
    <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="{{ asset('/js/plugins/jasny-bootstrap.min.js') }}"></script>
    <!-- Chartist JS -->
    <script src="{{ asset('/js/plugins/chartist.min.js') }}"></script>


    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('/js/core/material-dashboard.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/arq.js') }}" type="text/javascript"></script>

    <script>
      $(document).ready(function() {

        $sidebar = $('.sidebar');
        $sidebar_img_container = $sidebar.find('.sidebar-background');
        $full_page = $('.full-page');
        $sidebar_responsive = $('body > .navbar-collapse');
        window_width = $(window).width();
        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        $sidebar_img_container.css('background-size', 'contain');

        $.ajax({
          type: 'GET',
          url: '/json/check_cash_flow',
          dataType: 'json',
          success: function(data){
            if(data.status)
            {
              $('.cash_flow').removeClass('hidden');
            }
          },
          error: function(xhr, status){
            Swal.fire({
              title: 'Hubo un error al Consultar Contador de Aprobaciones!',
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Aceptar'
            });
          }
        });

      });
    </script>

    @yield('javascript')
</body>
</html>
