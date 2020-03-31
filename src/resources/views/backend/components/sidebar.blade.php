<div class="sidebar" data-color="purple" data-background-color="black" data-image="{{ asset('img/background_sidebar.jpg') }}" >
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"
    Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="{{ url('/') }}" class="simple-text logo-normal text-header-frame">
      ARQ
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
              <a class="nav-link" href="{{ url('/') }}">
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
        <a class="nav-link" href="{{ url('/') }}">
          <i class="material-icons">list_alt</i>
          <p> Obras </p>
        </a>
      </li>

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
              <a class="nav-link" href="{{ url('/') }}">
                <span class="sidebar-mini"> U </span>
                <span class="sidebar-normal"> Usuarios </span>
              </a>
            </li>
            <li class="nav-item nav-check-url ">
              <a class="nav-link" href="{{ url('/') }}">
                <span class="sidebar-mini"> R </span>
                <span class="sidebar-normal"> Roles </span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item nav-check-url ">
        <a class="nav-link none-alert" data-toggle="collapse" href="#utilities-nav-menu">
          <i class="material-icons">list</i>
          <p> Utilidades
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="utilities-nav-menu">
          <ul class="nav">
            <li class="nav-item nav-check-url ">
              <a class="nav-link" href="{{ url('/') }}">
                <span class="sidebar-mini"> TP </span>
                <span class="sidebar-normal"> Tipos de Obras </span>
              </a>
            </li>
            <li class="nav-item nav-check-url ">
                <a class="nav-link" href="{{ url('/') }}">
                  <span class="sidebar-mini"> FN </span>
                  <span class="sidebar-normal"> Feriados Nacionales </span>
                </a>
            </li>
            <li class="nav-item nav-check-url ">
              <a class="nav-link" href="{{ url('/') }}">
                <span class="sidebar-mini"> NE </span>
                <span class="sidebar-normal"> Niveles Educativos </span>
              </a>
            </li>
            <li class="nav-item nav-check-url ">
              <a class="nav-link" href="{{ url('/') }}">
                <span class="sidebar-mini"> P </span>
                <span class="sidebar-normal"> Paises </span>
              </a>
            </li>
          </ul>
        </div>
      </li>

    </ul>
  </div>
</div>
