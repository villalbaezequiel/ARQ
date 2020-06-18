@extends('layouts.admin')

@section('title', "Clientes / Ver")

@section('content')

<?php $user = \App\User::find($client->id_user); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <form action="#" class="form-horizontal form-disabled">
        <div class="card ">
          <div class="card-header card-header-primary card-header-text">
            <div class="card-text">
              <h4 class="card-title">Ver Cliente</h4>
            </div>
          </div>
          <div class="card-body ">

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
              <label class="col-md-2 col-form-label">Apellido <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control" type="text" name="last_name" value="{{ $user->last_name }}" maxlength="125" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Nombre <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control" type="text" name="name" value="{{ $user->name }}" maxlength="125" required>
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
              <label class="col-md-2 col-form-label">Email <span class="required-red">*</span></label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control" type="email" name="email" value="{{ $user->email }}" maxlength="125" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label label-checkbox">Activo</label>
              <div class="col-md-10 checkbox-radios">
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" value="1" name="is_active" <?php if($user->is_active == "1"){ echo "checked"; } ?>> Si
                    <span class="form-check-sign">
                      <span class="check"></span>
                    </span>
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" value="0" name="is_active" <?php if($user->is_active == "0"){ echo "checked"; } ?>> No
                    <span class="form-check-sign">
                      <span class="check"></span>
                    </span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row" style="padding: 15px;">
              <label class="col-md-12 col-form-label">
                <center><strong>Datos Contador/Secretaria/Otro</strong></center>
              </label>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Apellido</label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control" type="text" name="contact_last_name" value="{{ $client->contact_last_name }}" maxlength="125">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Nombre</label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control" type="text" name="contact_name" value="{{ $client->contact_name }}" maxlength="125">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">DNI</label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control input-type-number" type="number" name="contact_dni" value="{{ $client->contact_dni }}" minlength="8" maxlength="8">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Teléfono</label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="contact_telephone" value="{{ $client->contact_telephone }}" maxlength="125">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Otro Teléfono</label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="contact_other_telephone" value="{{ $client->contact_other_telephone }}" maxlength="125">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Email</label>
              <div class="col-md-7">
                <div class="form-group">
                  <input class="form-control" type="email" name="contact_email" value="{{ $client->contact_email }}" maxlength="125">
                </div>
              </div>
            </div>

            <div class="row p-2">
                <label class="col-md-12 col-form-label">
                    <center><strong>Datos de facturación</strong></center>
                </label>
            </div>

            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#billing_1">
                  Facturación 1 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="billing_1" class="collapse show">
              <div class="row">
                <label class="col-md-2 col-form-label">Razón Social / Nombre</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="billing_razon_social" value="{{ $client->billing_razon_social }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">CUIT</label>
                <div class="col-md-8">
                  <div class="form-group">
                  <input class="form-control input-type-number" type="number" name="billing_cuit" value="{{ $client->billing_cuit }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Ingresos brutos</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="number" name="billing_gross_income" value="{{ $client->billing_gross_income }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Condicion de IVA</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" name="billing_condition_iva">
                      <option value="0" <?php if($client->billing_condition_iva == "0"){ echo "selected='selected'"; } ?>>No Inscripto</option>
                      <option value="1" <?php if($client->billing_condition_iva == "1"){ echo "selected='selected'"; } ?>>Inscripto</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Pais</label>
                <div class="col-md-3">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="billing_id_country">
                      @foreach(\App\Helpers::getCountries() as $key => $val)
                        <option value="{{ $key }}" <?php if($client->billing_id_country == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="billing_id_province">
                      @foreach(\App\Helpers::getProvinces() as $key => $val)
                        <option value="{{ $key }}" <?php if($client->billing_id_province == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <input class="form-control" type="text" name="billing_location" value="{{ $client->billing_location }}" placeholder="Localidad">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Dirección</label>
                <div class="col-md-4">
                  <div class="form-group">
                    <input class="form-control" type="text" name="billing_address" value="{{ $client->billing_address }}">
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="billing_address_number" value="{{ $client->billing_address_number }}" placeholder="Número">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <input class="form-control" type="text" name="billing_piso_depto" value="{{ $client->billing_piso_depto }}" placeholder="Piso/Depto">
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="billing_cp" value="{{ $client->billing_cp }}" placeholder="CP">
                  </div>
                </div>
              </div>
            </div>

            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#billing_2">
                  Facturación 2 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="billing_2" class="collapse">
              <div class="row">
                <label class="col-md-2 col-form-label">Razón Social / Nombre</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="billing_razon_social_2" value="{{ $client->billing_razon_social_2 }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">CUIT</label>
                <div class="col-md-8">
                  <div class="form-group">
                  <input class="form-control input-type-number" type="number" name="billing_cuit_2" value="{{ $client->billing_cuit_2 }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Ingresos brutos</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="number" name="billing_gross_income_2" value="{{ $client->billing_gross_income_2 }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Condicion de IVA</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" name="billing_condition_iva_2">
                      <option value="0" <?php if($client->billing_condition_iva_2 == "0"){ echo "selected='selected'"; } ?>>No Inscripto</option>
                      <option value="1" <?php if($client->billing_condition_iva_2 == "1"){ echo "selected='selected'"; } ?>>Inscripto</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Pais</label>
                <div class="col-md-3">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="billing_id_country_2">
                      @foreach(\App\Helpers::getCountries() as $key => $val)
                        <option value="{{ $key }}" <?php if($client->billing_id_country_2 == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="billing_id_province_2">
                      @foreach(\App\Helpers::getProvinces() as $key => $val)
                        <option value="{{ $key }}" <?php if($client->billing_id_province_2 == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <input class="form-control" type="text" name="billing_location_2" value="{{ $client->billing_location_2 }}" placeholder="Localidad">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Dirección</label>
                <div class="col-md-4">
                  <div class="form-group">
                    <input class="form-control" type="text" name="billing_address_2" value="{{ $client->billing_address_2 }}">
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="billing_address_number_2" value="{{ $client->billing_address_number_2 }}" placeholder="Número">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <input class="form-control" type="text" name="billing_piso_depto_2" value="{{ $client->billing_piso_depto_2 }}" placeholder="Piso/Depto">
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="billing_cp_2" value="{{ $client->billing_cp_2 }}" placeholder="CP">
                  </div>
                </div>
              </div>
            </div>

            <div class="row" style="padding: 15px;">
                <label class="col-md-12 col-form-label">
                    <center><strong>Documentos</strong></center>
                </label>
            </div>

            @if(count($user_files))
            <div class="row">
              <div class="col-md-10 mx-auto">
                <table class="table">
                  <thead>
                    <tr>
                      <th><strong>Nombre</strong></th>
                      <th><strong>Path</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($user_files as $file)
                    <tr>
                      <td>{{ $file->path_label }}</td>
                      <td><a href="{{ url($file->path) }}" target="_blank">{{ $file->path }}</a></td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            @endif

          </div>
          <div class="card-footer ml-auto mr-auto">
            <a href='{{ url("/clients") }}' class="back-history btn">Volver</a>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
@section('javascript')
<script>
$(document).ready(function(){
  $(".form-disabled input, .form-disabled select").attr('disabled', true);
});
</script>
@endsection
