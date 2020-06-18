@extends('layouts.admin')

@section('title', "Proveedores")

@section('content')
<div class="container-fluid">

  <a href="{{ url('/providers/create') }}" class="btn btn-primary">Nuevo Proveedor</a>
  <a href='{{ url("/providers") }}' class="btn btn-primary">Descargar Lista</a>

  <div class="row">
    <div class="col-md-12">
      <div class="card">

        <div class="card-header card-header-primary card-header-icon">
          <div class="card-icon">
            <i class="material-icons">assignment</i>
          </div>
          <h4 class="card-title">Lista</h4>
        </div>
        <div class="card-body">
          @if (session('message'))
            <div class="toolbar">
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
          @endif
          <table id="datatables" class="table table-hover table-striped table-bordered nowrap" style="width:100%">
            <thead>
              <tr>
                <th><strong>#ID</strong></th>
                <th><strong>Nombre/Razon Social</strong></th>
                <th><strong>Activo</strong></th>
                <th class="text-right"><strong>Acciones </strong></th>
              </tr>
            </thead>
            <tbody>
              @foreach($providers as $provider)
              <tr class="item-alert-confirm-delete">
                <td>{{ $provider->id }}</td>
                <td>{{ \App\User::getFullName($provider->id_user) }}</td>
                <td>{{ \App\User::getActive($provider->id_user) }}</td>
                <td class="text-right">
                  {!! Form::open(['route' => ['providers.destroy', $provider->id], 'method' => 'DELETE', 'class'=> 'alert-confirm-delete']) !!}
                    <a href="{{ url('/providers/'.$provider->id) }}" class="btn btn-link btn-warning btn-just-icon show" title="Ver"><i class="material-icons">dvr</i></a>
                    <a href="{{ url('/providers/'.$provider->id.'/edit') }}" class="btn btn-link btn-warning btn-just-icon edit" title="Editar"><i class="material-icons">dvr</i></a>
                    <button type="submit" class="btn btn-link btn-danger btn-just-icon remove" title="Eliminar"><i class="material-icons">close</i></button>
                  {!! Form::close() !!}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div><!-- end card-body-->

      </div><!--  end card  -->
    </div><!-- end col-md-12 -->
  </div><!-- end row -->
</div>
@endsection
@section('javascript')
<script>
$(document).ready(function(){
  $(".alert-confirm-delete").submit(function(e) {
    e.preventDefault();
    alertConfirmDeleteItem(this);
  });
});
</script>
@endsection
