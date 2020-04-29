@extends('layouts.admin')

@section('title', "Proyectos")

@section('content')
<div class="container-fluid">

    <a href="{{ url('/projects/create') }}" class="btn btn-primary">Nuevo Proyecto</a>
    <a href='{{ url("/") }}' class="btn btn-primary">Descargar Lista</a>
  

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
                <th>Nombre</th>
                <th>Cliente/s</th>
                <th>Fechas: Inicio - Finalización</th>
                <th>Activo</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($projects as $project)

              <?php
                $start_date  = explode('-', \App\Helpers::getDate($project->start_date));
                $finish_date = explode('-', \App\Helpers::getDate($project->finish_date));
                $clients     = '';

                if(json_decode($project->id_client))
                {
                  foreach(json_decode($project->id_client) as $key => $val)
                  {
                    $clients .= \App\Client::getName($val) . ', ';
                  }
                }
              ?>

              <tr class="item-alert-confirm-delete">
                <td>{{ $project->name }}</td>
                <td>{{ $clients }}</td>
                <td>{{ $start_date[2].'/'.$start_date[1].'/'.$start_date[0] }} - {{ $finish_date[2].'/'.$finish_date[1].'/'.$finish_date[0] }}</td>
                <td>{{ \App\Helpers::getActive($project->active) }}</td>
                <td class="text-right">
                  {!! Form::open(['route' => ['projects.destroy', $project->id], 'method' => 'DELETE', 'class'=> 'alert-confirm-delete', 'style'=> 'text-align: center;']) !!}
                    <a href="{{ url('/projects/'.$project->id.'/edit') }}" class="btn btn-link btn-warning btn-just-icon edit" title="Editar"><i class="material-icons">dvr</i></a>
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
