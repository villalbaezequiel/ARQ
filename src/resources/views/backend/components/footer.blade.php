<footer class="footer">
  <div class="container-fluid">
    <nav class="float-left">
      <ul>
        <li>
          <a>
            Version {{ env('DEV_VERSION') }}
          </a>
        </li>
        {{-- <li>
          <a href="http://google.com/" target="_blank">
            ARQ - Obras
          </a>
        </li>
        <li>
          <a href="http://www.google.com/" target="_blank">
          Piche Solutions
          </a>
        </li> --}}
      </ul>
    </nav>
    <div class="copyright float-right">
      &copy;{{ date('Y') }} - 
      <a href="http://www.google.com/" class="none-alert" target="_blank">Piche Solutions</a> para una mejor web.
    </div>
  </div>
</footer>
