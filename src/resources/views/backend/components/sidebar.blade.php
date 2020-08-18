<div class="sidebar" data-color="purple" data-background-color="black">
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"
    Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="{{ url('/') }}" class="simple-text logo-normal text-center">
      <img src="{{ asset('/img/logo_blanco.png') }}" width="130" />
    </a>
  </div>
  <div class="sidebar-wrapper">
    <div class="user">
      <div class="photo">
        <img src="{{ asset('/img/avatar_male_default.png') }}" />
      </div>
      <div class="user-info">
        <a data-toggle="collapse" href="#collapseExample" class="username none-alert">
          <span>
            {{ Auth::user()->name.' '.Auth::user()->last_name }}
            <b class="caret"></b>
          </span>
        </a>
        <div class="collapse" id="collapseExample">
          <ul class="nav">
            <li class="nav-item nav-check-url">
              <a class="nav-link" href="{{ url('/users').'/'.Auth::user()->id.'/edit' }}">
                <span class="sidebar-mini"> EP </span>
                <span class="sidebar-normal"> Editar Perfil </span>
              </a>
            </li>

            <li class="nav-item">
              <a 
                class="nav-link"
                href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                  <span class="sidebar-mini"> CS </span>
                  <span class="sidebar-normal"> Cerrar Sesión </span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <ul class="nav">

      <li class="nav-item nav-check-url ">
        <a class="nav-link" href="{{ url('/projects') }}">
          <i class="material-icons">list_alt</i>
          <p> Proyectos </p>
        </a>
      </li>

      @if(Auth::user()->permissions == "admin")
        <li class="nav-item nav-check-url ">
          <a class="nav-link" href="{{ url('/project_cash_flow') }}">
            <i class="material-icons">menu_book</i>
            <p> Aprobaciones <i class="fa fa-tag hidden cash_flow" style="float:right; color:red"></i></p>
          </a>
        </li>

        <li class="nav-item nav-check-url ">
          <a class="nav-link none-alert" data-toggle="collapse" href="#items">
            <i class="material-icons">group_work</i>
            <p> Rubros
              <b class="caret"></b>
            </p>
          </a>
          <div class="collapse" id="items">
            <ul class="nav">
              <li class="nav-item nav-check-url ">
                <a class="nav-link" href="{{ url('/items') }}">
                  <span class="sidebar-mini"> R </span>
                  <span class="sidebar-normal"> Rubros </span>
                </a>
              </li>
              <li class="nav-item nav-check-url ">
                <a class="nav-link" href="{{ url('/sub_items') }}">
                  <span class="sidebar-mini"> SR </span>
                  <span class="sidebar-normal"> Sub Rubros </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
      @endif

      <li class="nav-item nav-check-url ">
        <a class="nav-link" href="{{ url('/clients') }}">
          <i class="material-icons">people</i>
          <p> Clientes </p>
        </a>
      </li>

      <li class="nav-item nav-check-url ">
        <a class="nav-link" href="{{ url('/providers') }}">
          <i class="material-icons">people_outline</i>
          <p> Proveedores </p>
        </a>
      </li>

      @if(Auth::user()->permissions == "admin")
      <li class="nav-item nav-check-url ">
        <a class="nav-link none-alert" data-toggle="collapse" href="#users-nav-menu">
          <i class="material-icons">people</i>
          <p> Usuarios
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="users-nav-menu">
          <ul class="nav">
            <li class="nav-item nav-check-url ">
              <a class="nav-link" href="{{ url('/users') }}">
                <span class="sidebar-mini"> U </span>
                <span class="sidebar-normal"> Usuarios </span>
              </a>
            </li>
            <li class="nav-item nav-check-url ">
              <a class="nav-link" href="{{ url('/roles') }}">
                <span class="sidebar-mini"> R </span>
                <span class="sidebar-normal"> Roles </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      @endif

    </ul>
  </div>
</div>