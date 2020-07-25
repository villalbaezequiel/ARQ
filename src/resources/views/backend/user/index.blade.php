@extends('layouts.admin')

@section('title', "Usuarios")

@section('content')
<div class="container-fluid">

  @if(Auth::user()->permissions == "admin")
    <a href="{{ url('/users/create') }}" class="btn btn-primary">Nuevo Usuario</a>
  @endif

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
                <th>Nombre/Apellido</th>
                <th>Rol</th>
                <th>Activo</th>
                <th class="text-right">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
              <tr class="item-alert-confirm-delete">
                <td>{{ $user->getFullNameAttribute() }}</td>
                <td>{{ $user->getRole() }}</td>
                <td>{{ \App\User::getActive($user->id) }}</td>
                <td class="text-right">
                @if(Auth::user()->permissions == "admin")
                  {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'DELETE', 'class'=> 'alert-confirm-delete']) !!}
                    <a href="{{ url('/users/'.$user->id) }}" class="btn btn-link btn-warning btn-just-icon show" title="Ver"><i class="material-icons">visibility</i></a>
                    <a href="{{ url('/users/'.$user->id.'/edit') }}" class="btn btn-link btn-warning btn-just-icon edit" title="Editar"><i class="material-icons">dvr</i></a>
                    <button type="submit" class="btn btn-link btn-danger btn-just-icon remove" title="Eliminar"><i class="material-icons">close</i></button>
                  {!! Form::close() !!}
                @else
                  <a href="{{ url('/users/'.$user->id) }}" class="btn btn-link btn-warning btn-just-icon show" title="Ver"><i class="material-icons">visibility</i></a>
                @endif
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
