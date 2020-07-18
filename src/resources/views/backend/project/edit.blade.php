@extends('layouts.admin')

@section('title', "Proyectos / Editar")

@section('content')

<div class="container-fluid">
  <div class="col-md-12 col-12 mr-auto ml-auto">

    @if (session('message'))
      <div class="toolbar">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          {{ session('message') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    @endif

    <div class="wizard-container">
      <div class="card card-wizard active" data-color="purple" id="wizardProfile">

        {!! Form::open(['route' => ['projects.update', $project->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'onKeyPress' => 'return disableEnterKey(event)', 'id' => 'logic_project', 'enctype' => 'multipart/form-data']) !!}
          <div class="card-header text-center">
            <h3 class="card-title">
              Editar Proyecto
            </h3>
          </div>
          <div class="wizard-navigation">
            <ul class="nav nav-pills">
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link active" href="#module_one" data-toggle="tab" role="tab">
                  Información
                </a>
              </li>

              @if(Auth::user()->permissions == "admin")
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link" href="#module_two" data-toggle="tab" role="tab">
                  Presupuesto
                </a>
              </li>
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link" href="#module_three" data-toggle="tab" role="tab">
                  Seguimiento
                </a>
              </li>
              @endif

              @if(Auth::user()->permissions != "junior")
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link" href="#module_five" data-toggle="tab" role="tab">
                  Flujo de caja
                </a>
              </li>
              <li class="nav-item" style="width: 50%;">
                <a class="nav-link" href="#module_six" data-toggle="tab" role="tab">
                  Proveedores
                </a>
              </li>
              @endif

              <li class="nav-item" style="width: 50%;">
                <a class="nav-link" href="#module_four" data-toggle="tab" role="tab">
                  Documentos
                </a>
              </li>
            </ul>

            <div class="moving-tab" style="width: 50%; transform: translate3d(-8px, 0px, 0px); transition: transform 0s ease 0s;">
              Informacion
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content">

              <div class="tab-pane active" id="module_one">
                <h5 class="info-text"> Información General </h5>
                <div class="row justify-content-center">

                  <div class="col-md-5">
                    <div class="card ">
                      <div class="card-header card-header-rose card-header-text">
                        <div class="card-icon card-icon-project">
                          <i class="material-icons">library_books</i>
                        </div>
                        <h4 class="card-title">Fecha de Inicio</h4>
                      </div>
                      <div class="card-body ">
                        <div class="form-group is-filled">
                          <?php $start_date = explode('-', \App\Helpers::getDate($project->start_date)); ?>
                          <input type="text" class="form-control datepicker" value="{{ $start_date[2].'/'.$start_date[1].'/'.$start_date[0] }}" name="start_date" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="card ">
                      <div class="card-header card-header-rose card-header-text">
                        <div class="card-icon card-icon-project">
                          <i class="material-icons">library_books</i>
                        </div>
                        <h4 class="card-title">Fecha de Finalización</h4>
                      </div>
                      <div class="card-body ">
                        <div class="form-group is-filled">
                          <?php $finish_date = explode('-', \App\Helpers::getDate($project->finish_date)); ?>
                          <input type="text" class="form-control datepicker" value="{{ $finish_date[2].'/'.$finish_date[1].'/'.$finish_date[0] }}" name="finish_date" required>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Cliente<span class="required-red">*</span>
                        </span>
                        <select class="form-control selectpicker" data-style="btn btn-link" name="id_client[]" multiple required>
                          <option value="99999">Seleccionar</option>
                          @foreach($clients as $client)
                            <option value="{{ $client->id }}"
                              <?php foreach(json_decode($project->id_client) as $key => $val) {
                                if($val == $client->id){ echo "selected"; }
                              }?>
                            >
                              {{ $client->last_name .' '. $client->name }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Nombre<span class="required-red">*</span>
                        </span>
                        <input type="text" class="form-control" name="name" value="{{ $project->name }}" maxlength="125" required>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Descripción
                        </span>
                        <input type="text" class="form-control" name="description" value="{{ $project->description }}" maxlength="125">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Ubicacion
                        </span>
                        <div class="col-md-4">
                          <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="id_country">
                            @foreach(\App\Helpers::getCountries() as $key => $val)
                              <option value="{{ $key }}" <?php if($project->id_country == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-md-4">
                          <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="id_province">
                            @foreach(\App\Helpers::getProvinces() as $key => $val)
                              <option value="{{ $key }}" <?php if($project->id_province == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-2">
                          <input class="form-control" type="text" name="location" value="{{ $project->location }}" placeholder="Localidad">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Domicilio
                        </span>
                        <div class="col-md-4">
                          <input class="form-control" type="text" name="address" value="{{ $project->address }}">
                        </div>

                        <div class="col-md-2">
                          <input class="form-control input-type-number" type="text" name="address_number" value="{{ $project->address_number }}" placeholder="Número">
                        </div>
                        <div class="col-md-2">
                          <input class="form-control" type="text" name="piso_depto" value="{{ $project->piso_depto }}" placeholder="Piso/Depto">
                        </div>
                        <div class="col-md-2">
                          <input class="form-control input-type-number" type="text" name="cp" value="{{ $project->cp }}" placeholder="CP">
                        </div>
                      </div>
                    </div>
                  </div> 

                  <div class="col-md-10">
                    <div class="form-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Proyecto Activo
                        </span>
                        <select class="form-control selectpicker" data-style="btn btn-link" name="is_active">
                          <option value="1" <?php if($project->is_active == "1"){ echo "selected='selected'"; } ?>>Si</option>
                          <option value="0" <?php if($project->is_active == "0"){ echo "selected='selected'"; } ?>>No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                          
                </div>
              </div>

              @if(Auth::user()->permissions == "admin")
              <div class="tab-pane" id="module_two">
                <h5 class="info-text"> Presupuesto </h5>
                @include('backend.project.components.edit.budgets')
              </div>

              <div class="tab-pane" id="module_three">
                <h5 class="info-text"> Seguimiento </h5>
                @include('backend.project.components.edit.real_cost')
              </div>
              @endif

              @if(Auth::user()->permissions != "junior")
              <div class="tab-pane" id="module_five">
                <h5 class="info-text"> Flujo de Caja </h5>
                @include('backend.project.components.edit.cash_flow')
              </div>

              <div class="tab-pane" id="module_six">
                <h5 class="info-text"> Proveedores </h5>
                @include('backend.project.components.edit.providers')
              </div>
              @endif

              <div class="tab-pane" id="module_four">
                <h5 class="info-text"> Documentos </h5>
                @include('backend.project.components.edit.documents')
              </div>           

            </div>
          </div>
          <div class="card-footer">
            <div class="mr-auto">
              <input type="button" class="btn btn-previous btn-fill btn-default btn-wd disabled" name="previous" value="Anterior">
            </div>
            <div class="ml-auto">

              <textarea name="observations" class="hidden">{{ $project->observations }}</textarea>

              <input type="hidden" name="id_project" value="{{ $project->id }}">
              <input type="button" class="btn btn-next btn-fill btn-primary btn-wd" name="next" value="Siguiente">
              <input type="submit" class="btn btn-finish btn-fill btn-primary btn-wd show-content-observation" name="finish" value="Guardar" />
            </div>
            <div class="clearfix"></div>
          </div>

        {!! Form::close() !!}

        @include('backend.project.components.edit.documents_form_delete')

      </div>
    </div>

  </div>
</div>

<style>
  .btn-finish {
    display: inline-block!important;
  }
</style>
@endsection

@section('javascript')
  <script src="{{ asset('/js/project.js') }}"></script>
@endsection
