@extends('layouts.admin')

@section('title', "Flujo de Cajas")

@section('content')
<div class="container-fluid">

<?php 
$helpers = new \App\Helpers;
?>

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
                <th>Proyecto</th>
                <th>Rubro</th>
                <th>Sub-Rubro</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Accion</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cash_flows as $cash_flow)
              <tr class="item-alert-confirm-delete">
                <td>{{ \App\Project::getName($cash_flow->id_project) }}</td>
                <td>{{ \App\Item::getName($cash_flow->id_item) }}</td>
                <td>{{ \App\SubItem::getName($cash_flow->id_subitem) }}</td>
                <td>{{ $helpers->getFormatSalary($cash_flow->amount) }}</td>
                <td>{{ $helpers->setDateFormat($cash_flow->date, '-') }}</td>
                <td>{{ \App\User::getFullName($cash_flow->id_user) }}</td>
                <td class="text-right">
                  {!! Form::open(['route' => ['project_cash_flow.update', $cash_flow->id], 'method' => 'PUT', 'class' => 'alert-confirm-cashflow-approved', 'style' => 'float: left;']) !!}
                    <button type="submit" class="btn btn-link btn-warning btn-just-icon edit" title="Aprobar"><i class="material-icons">dvr</i></button>
                  {!! Form::close() !!}
                  {!! Form::open(['route' => ['project_cash_flow.destroy', $cash_flow->id], 'method' => 'DELETE', 'class'=> 'alert-confirm-delete', 'style' => 'float: right;']) !!}
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

  $(".alert-confirm-cashflow-approved").submit(function(e) {
    e.preventDefault();
    alertConfirmCashFloq(this);
  });
});
</script>
@endsection