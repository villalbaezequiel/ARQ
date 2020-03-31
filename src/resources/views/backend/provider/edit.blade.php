@extends('layouts.admin')

@section('title', "Teams / Editar")

@section('content')

<?php $user = \App\User::find($provider->id_user); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      {!! Form::open(['route' => ['providers.update' , $provider->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
        <div class="card ">
          <div class="card-header card-header-primary card-header-text">
            <div class="card-text">
              <h4 class="card-title">Editar Team</h4>
            </div>
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

          <div class="row">
              <label class="col-md-2 col-form-label">Nombre <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control" type="text" name="name" value="{{ $user->name }}" maxlength="125" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Apellido <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control" type="text" name="last_name" value="{{ $user->last_name }}" maxlength="125" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Email <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control" type="email" name="email" value="{{ $user->email }}" maxlength="125" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Rubro (Categoria) <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" name="id_category" required>
                    <option value="">Seleccionar</option>
                    <option value="1" <?php if($provider->id_category == '1'){ echo "selected='selected'"; } ?>>Iluminista</option>
                    <option value="2" <?php if($provider->id_category == '2'){ echo "selected='selected'"; } ?>>Electricista</option>
                    <option value="3" <?php if($provider->id_category == '3'){ echo "selected='selected'"; } ?>>Plomero</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Tipo de Documentación <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" name="type_dni" required>
                    @foreach(\App\Helpers::getTypesDocumentationPerson() as $key => $val)
                    <option value="{{ $key }}" <?php if($user->type_dni == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">DNI <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control input-type-number" type="number" name="dni" value="{{ $user->dni }}" minlength="8" maxlength="8" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">CUIT <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control input-type-number" type="number" name="cuit" value="{{ $user->cuit }}" maxlength="125" required>
                </div>
              </div>
              <label class="col-md-3 label-on-right">
                <code>inserte únicamente números.</code>
              </label>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Género <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" name="gender" required>
                    <option value="">Seleccionar</option>
                    @foreach(\App\Helpers::getGendersPerson() as $key => $val)
                    <option value="{{ $key }}" <?php if($user->gender == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Estado Civil <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" name="civil_status" required>
                    <option value="">Seleccionar</option>
                    @foreach(\App\Helpers::getCivilStatus() as $key => $val)
                    <option value="{{ $key }}" <?php if($user->civil_status == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Fecha de Nacimiento <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  @if($user->birthdate)
                    <?php $birthdate = explode('-', \App\Helpers::getDate($user->birthdate)); ?>
                    <input class="form-control datepicker validate-year" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="text" name="birthdate" value="{{ $birthdate[2].'/'.$birthdate[1].'/'.$birthdate[0] }}" minlength="10" maxlength="10" required readonly>
                  @else
                    <input class="form-control datepicker validate-year" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="text" name="birthdate" value="{{ \App\Helpers::getDate($user->birthdate) }}" minlength="10" maxlength="10" required readonly>
                  @endif
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Nivel de Estudio <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="id_study" required>
                    <option value="1" <?php if($user->id_study == '1'){ echo "selected='selected'"; } ?>>Secundario</option>
                    <option value="2" <?php if($user->id_study == '2'){ echo "selected='selected'"; } ?>>Terciario</option>
                    <option value="3" <?php if($user->id_study == '3'){ echo "selected='selected'"; } ?>>Universitario</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Nacionalidad <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="id_nationality" required>
                    <option value="1" <?php if($user->id_nationality == '1'){ echo "selected='selected'"; } ?>>Argentina/o</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Tiene Visa <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="visa" required>
                    <option value="1" <?php if($user->visa == '1'){ echo "selected='selected'"; } ?>>Si</option>
                    <option value="0" <?php if($user->visa == '0'){ echo "selected='selected'"; } ?>>No</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Dirección <span class="required-red">*</span></label>
              <div class="col-md-3">
                <div class="form-group">
                  <input class="form-control" type="text" name="address" value="{{ $user->address }}" required>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="address_number" value="{{ $user->address_number }}" placeholder="Número Dirección *" required>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input class="form-control" type="text" name="piso_depto" value="{{ $user->piso_depto }}" placeholder="Piso/Depto">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Pais <span class="required-red">*</span></label>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="country" required>
                    <option value="1">Argentina</option>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input class="form-control" type="text" name="location" value="{{ $user->location }}" placeholder="Localidad *" required>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="cp" value="{{ $user->cp }}" placeholder="Codigo Postal *" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Teléfono <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="telephone" value="{{ $user->telephone }}" maxlength="125" required>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-md-2 col-form-label">Otro Teléfono</label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="other_telephone" value="{{ $user->other_telephone }}" maxlength="125">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Obra Social</label>
              <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" name="social_work" onchange="frameJs.getChangeOs(this.value)">
                      <option value="0" <?php if($provider->social_work == '0'){ echo "selected"; } ?> >No</option>
                      <option value="1" <?php if($provider->social_work == '1'){ echo "selected"; } ?>>Si</option>
                    </select>
                </div>
              </div>
              <div id="social_work_name_content" class="col-md-3 <?php if($provider->social_work == '0') { echo "hidden";} ?>">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" name="social_work_name">
                    <option value="">Seleccionar</option>
                    @foreach(\App\Helpers::getListOs() as $key => $os)
                      <option value="{{ $key }}" <?php if($key == $provider->social_work_name){ echo "selected"; } ?>>{{ $os }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div id="social_work_code_content" class="col-md-2 <?php if($provider->social_work == '0') { echo "hidden";} ?>">
                <div class="form-group">
                  <input class="form-control" type="text" name="social_work_code" value="{{ $provider->social_work_code }}">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label label-checkbox">Proveedor Activo</label>
              <div class="col-md-10 checkbox-radios">
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                  <input class="form-check-input" type="radio" value="1" name="is_active" <?php if($user->is_active == 1){ echo "checked"; } ?>> Si
                    <span class="form-check-sign">
                      <span class="check"></span>
                    </span>
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                  <input class="form-check-input" type="radio" value="0" name="is_active" <?php if($user->is_active == 0){ echo "checked"; } ?>> No
                    <span class="form-check-sign">
                      <span class="check"></span>
                    </span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row" style="padding: 15px;">
                <label class="col-md-12 col-form-label">
                    <center><strong>Datos Bancarios</strong></center>
                </label>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Banco <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="bank" required>
                    <option value="">Seleccionar</option>
                    @foreach(\App\Helpers::getListsBank() as $k => $v)
                    <option value="{{ $k }}" <?php if($k == $provider->bank) { echo "selected"; } ?>>{{ $v }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-md-2 col-form-label">CBU <span class="required-red">*</span></label>
              <div class="col-md-7">
                  <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="cbu" value="{{ $provider->cbu }}" minlength="22" maxlength="22" required>
                  </div>
              </div>
              <label class="col-md-3 label-on-right">
                  <code>inserte únicamente números.</code>
              </label>
            </div>
            <div class="row">
              <label class="col-md-2 col-form-label">Tipo de Cuenta <span class="required-red">*</span></label>
                <div class="col-md-7">
                  <div class="form-group">
                    <input class="form-control" type="text" name="type_account" value="{{ $provider->type_account }}" required>
                  </div>
              </div>
            </div>
            <div class="row">
              <label class="col-md-2 col-form-label">N* de Cuenta <span class="required-red">*</span></label>
              <div class="col-md-7">
                  <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="number_account" value="{{ $provider->number_account }}" required>
                  </div>
              </div>
              <label class="col-md-3 label-on-right">
                  <code>inserte únicamente números.</code>
              </label>
            </div>
            <div class="row">
              <label class="col-md-2 col-form-label">Alias</label>
                <div class="col-md-7">
                  <div class="form-group">
                    <input class="form-control" type="text" name="alias_account" value="{{ $provider->alias_account }}">
                  </div>
              </div>
            </div>

            <div class="row" style="padding: 15px;">
                <label class="col-md-12 col-form-label">
                    <center><strong>Ducumentos</strong></center>
                </label>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group text-center">
                  <label class="btn" for="file_id">Seleccionar Documentos</label>
                  <input type="file" name="files[]" id="file_id" multiple>
                </div>
              </div>
            </div>

            @if(count($user_files))
            <div class="row">
              <div class="col-md-10 mx-auto">
                <table class="table">
                  <thead>
                    <tr>
                      <th><strong>Nombre</strong></th>
                      <th><strong>Path</strong></th>
                      <th class="text-right"><strong>Acciones </strong></th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($user_files as $file)
                    <tr>
                      <td>{{ $file->path_label }}</td>
                      <td><a href="{{ url($file->path) }}" target="_blank">{{ $file->path }}</a></td>
                      <td class="text-right">
                        <input type="submit" class="btn" value="Eliminar" form="file_{{ $file->id }}">
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            @endif

            <div class="row" style="padding: 15px;">
              <label class="col-md-12 col-form-label">
                <center><strong>Agregar Valoración</strong></center>
              </label>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Obras</label>
              <div class="col-md-7">
                <div class="form-group">
                  <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="valoration_id_project">
                    <option value="">Seleccionar</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-md-2 col-form-label">Descripción</label>
              <div class="col-md-7">
                <div class="form-group">
                  <textarea class="form-control" name="valoration_description" maxlength="125"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <label class="col-md-2 col-form-label label-checkbox">Puntaje</label>
              <div class="col-md-10 checkbox-radios">
                <div class="form-check form-check-inline valoration_content">
                  <input class="form-check-input valoration_star_input" id="radio5" type="radio" value="5" name="valoration_value">
                  <label class="form-check-label valoration_star" for="radio5">★</label>

                  <input class="form-check-input valoration_star_input" id="radio4" type="radio" value="4" name="valoration_value">
                  <label class="form-check-label valoration_star" for="radio4">★</label>

                  <input class="form-check-input valoration_star_input" id="radio3" type="radio" value="3" name="valoration_value">
                  <label class="form-check-label valoration_star" for="radio3">★</label>

                  <input class="form-check-input valoration_star_input" id="radio2" type="radio" value="2" name="valoration_value">
                  <label class="form-check-label valoration_star" for="radio2">★</label>

                  <input class="form-check-input valoration_star_input" id="radio1" type="radio" value="1" name="valoration_value">
                  <label class="form-check-label valoration_star" for="radio1">★</label>
                </div>
              </div>
            </div>
          
          </div>

          <div class="card-footer ml-auto mr-auto">
            <!-- Hide Val-->
            <input type="hidden" name="id_user" value="{{ $user->id }}" />
            <a href="javascript:void(0)" class="back-history btn">Cancelar</a>
            <input type="submit" class="btn btn-primary" value="Guardar" />
          </div>
        </div>
        {!! Form::close() !!}

        @if(count($user_files))
          @foreach($user_files as $file)
            {!! Form::open(['route' => ['user_files.destroy', $file->id], 'method' => 'DELETE', 'id' => "file_$file->id"]) !!}
            {!! Form::close() !!}
          @endforeach
        @endif

    </div>
  </div>
</div>
@endsection
@section('javascript')
<script>
$(document).ready(function(){
  $('a').click(function(e){

    if(!$(this).hasClass('none-alert')){
      var parentForm = $(this).parents('form');
      if(parentForm.length != 1 || $(this).hasClass('back-history')){
        e.preventDefault();
        alertLeaveForm(this);
      }
    }

  });
  
});
</script>
@endsection
