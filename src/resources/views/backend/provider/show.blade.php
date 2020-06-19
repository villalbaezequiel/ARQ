@extends('layouts.admin')

@section('title', "Proveedores / Ver")

@section('content')

<?php $user = \App\User::find($provider->id_user); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <form action="#" class="form-horizontal form-disabled">
        <div class="card ">
          <div class="card-header card-header-primary card-header-text">
            <div class="card-text">
              <h4 class="card-title">Ver Proveedor</h4>
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
              <label class="col-md-2 col-form-label">Nombre o Razon Social <span class="required-red">*</span></label>
              <div class="col-md-8">
                <div class="form-group">
                  <input class="form-control" type="text" name="name" value="{{ $user->name }}" maxlength="125" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">CUIT <span class="required-red">*</span></label>
              <div class="col-md-8">
                <div class="form-group">
                  <input class="form-control input-type-number" type="number" name="cuit" value="{{ $user->cuit }}" maxlength="125" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Rubro <span class="required-red">*</span></label>
              <div class="col-md-8">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="id_category[]" multiple required>
                    <option value="">Seleccionar</option>
                    @foreach($items as $item)
                    <option value="{{ $item->id }}"
                       <?php foreach(json_decode($provider->id_category) as $key => $val) {
                          if($val == $item->id){ echo "selected"; }
                        }?>>
                       {{ $item->name }}
                     </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Pais</label>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="id_country">
                    @foreach(\App\Helpers::getCountries() as $key => $val)
                      <option value="{{ $key }}" <?php if($user->id_country == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="id_province">
                    @foreach(\App\Helpers::getProvinces() as $key => $val)
                      <option value="{{ $key }}" <?php if($user->id_province == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input class="form-control" type="text" name="location" value="{{ $user->location }}" placeholder="Localidad">
                </div>
              </div>
            </div>
          
            <div class="row">
              <label class="col-md-2 col-form-label">Dirección</label>
              <div class="col-md-4">
                <div class="form-group">
                  <input class="form-control" type="text" name="address" value="{{ $user->address }}">
                </div>
              </div>
              <div class="col-md-1">
                <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="address_number" value="{{ $user->address_number }}" placeholder="Número">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input class="form-control" type="text" name="piso_depto" value="{{ $user->piso_depto }}" placeholder="Piso/Depto">
                </div>
              </div>
              <div class="col-md-1">
                <div class="form-group">
                  <input class="form-control input-type-number" type="text" name="cp" value="{{ $user->cp }}" placeholder="CP">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Condicion de IVA</label>
              <div class="col-md-8">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" name="condition_iva">
                    <option value="0" <?php if($provider->condition_iva == "0"){ echo "selected='selected'"; } ?>>No</option>
                    <option value="1" <?php if($provider->condition_iva == "1"){ echo "selected='selected'"; } ?>>Si</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Retiene Ingresos brutos</label>
              <div class="col-md-8">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" name="retains_gross_income">
                    <option value="0" <?php if($provider->retains_gross_income == "0"){ echo "selected='selected'"; } ?>>No</option>
                    <option value="1" <?php if($provider->retains_gross_income == "2"){ echo "selected='selected'"; } ?>>Si</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">% Retiene Ingresos brutos</label>
              <div class="col-md-8">
                <div class="form-group">
                  <input class="form-control input-type-number" type="number" name="retains_gross_income_porcentage" value="{{ $provider->retains_gross_income_porcentage }}" maxlength="125">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-2 col-form-label">Tipo de factura <span class="required-red">*</span></label>
              <div class="col-md-8">
                <div class="form-group">
                  <select class="form-control selectpicker" data-style="btn btn-link" name="invoice_type">
                    <option value="A" <?php if($provider->invoice_type == "A") { echo "selected='selected'"; } ?>>A</option>
                    <option value="B" <?php if($provider->invoice_type == "B") { echo "selected='selected'"; } ?>>B</option>
                    <option value="C" <?php if($provider->invoice_type == "C") { echo "selected='selected'"; } ?>>C</option>
                  </select>
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

            <div class="row p-2">
                <label class="col-md-12 col-form-label">
                    <center><strong>Datos de Contacto</strong></center>
                </label>
            </div>

            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_1">
                  Contacto 1 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="contact_1" class="collapse show">
              <div class="row">
                <label class="col-md-2 col-form-label">Nombre <span class="required-red">*</span></label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="contact_name" value="{{ $provider->contact_name }}" maxlength="125" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Cargo <span class="required-red">*</span></label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="contact_cargo" value="{{ $provider->contact_cargo }}" maxlength="125" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Telefono <span class="required-red">*</span></label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="contact_telephone" value="{{ $provider->contact_telephone }}" maxlength="125" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Email <span class="required-red">*</span></label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="email" name="contact_email" value="{{ $provider->contact_email }}" maxlength="125" required>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_2">
                  Contacto 2 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="contact_2" class="collapse">
              <div class="row">
                <label class="col-md-2 col-form-label">Nombre</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="contact_name_2" value="{{ $provider->contact_name_2 }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Cargo</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="contact_cargo_2" value="{{ $provider->contact_cargo_2 }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Telefono</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="contact_telephone_2" value="{{ $provider->contact_telephone_2 }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Email</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="email" name="contact_email_2" value="{{ $provider->contact_email_2 }}" maxlength="125">
                  </div>
                </div>
              </div>
            </div>

            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_3">
                  Contacto 3 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="contact_3" class="collapse">
              <div class="row">
                <label class="col-md-2 col-form-label">Nombre</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="contact_name_3" value="{{ $provider->contact_name_3 }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Cargo</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="contact_cargo_3" value="{{ $provider->contact_cargo_3 }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Telefono</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="contact_telephone_3" value="{{ $provider->contact_telephone_3 }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-md-2 col-form-label">Email</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="email" name="contact_email_3" value="{{ $provider->contact_email_3 }}" maxlength="125">
                  </div>
                </div>
              </div>
            </div>

            <div class="row" style="padding: 15px;">
                <label class="col-md-12 col-form-label">
                    <center><strong>Datos Bancarios</strong></center>
                </label>
            </div>

            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_bank_1">
                  Contacto 1 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="contact_bank_1" class="collapse show">
              <div class="row">
                <label class="col-md-2 col-form-label">Banco</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="bank">
                      <option value="">Seleccionar</option>
                      @foreach(\App\Helpers::getListsBank() as $k => $v)
                      <option value="{{ $k }}" <?php if($provider->bank == $k){ echo "selected='selected'"; } ?>>{{ $v }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">Tipo de Cuenta</label>
                  <div class="col-md-8">
                    <div class="form-group">
                      <select class="form-control selectpicker" data-style="btn btn-link" name="type_account">
                        @foreach(\App\Provider::getTipoCuenta() as $key => $val)
                        <option value="{{ $key }}" <?php if($provider->type_account == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">N* de Cuenta</label>
                <div class="col-md-8">
                    <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="number_account" value="{{ $provider->number_account }}">
                    </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">CBU</label>
                <div class="col-md-8">
                    <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="cbu" value="{{ $provider->cbu }}" minlength="22" maxlength="22">
                    </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">Alias</label>
                  <div class="col-md-8">
                    <div class="form-group">
                      <input class="form-control" type="text" name="alias_account" value="{{ $provider->alias_account }}">
                    </div>
                </div>
              </div>
            </div>

            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_bank_2">
                  Contacto 2 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="contact_bank_2" class="collapse">
              <div class="row">
                <label class="col-md-2 col-form-label">Banco</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="bank_2">
                      <option value="">Seleccionar</option>
                      @foreach(\App\Helpers::getListsBank() as $k => $v)
                      <option value="{{ $k }}" <?php if($provider->bank_2 == $k){ echo "selected='selected'"; } ?>>{{ $v }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">Tipo de Cuenta</label>
                  <div class="col-md-8">
                    <div class="form-group">
                      <select class="form-control selectpicker" data-style="btn btn-link" name="type_account_2">
                        @foreach(\App\Provider::getTipoCuenta() as $key => $val)
                        <option value="{{ $key }}" <?php if($provider->type_account_2 == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">N* de Cuenta</label>
                <div class="col-md-8">
                    <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="number_account_2" value="{{ $provider->number_account_2 }}">
                    </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">CBU</label>
                <div class="col-md-8">
                    <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="cbu_2" value="{{ $provider->cbu_2 }}" minlength="22" maxlength="22">
                    </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">Alias</label>
                  <div class="col-md-8">
                    <div class="form-group">
                      <input class="form-control" type="text" name="alias_account_2" value="{{ $provider->alias_account_2 }}">
                    </div>
                </div>
              </div>
            </div>

            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_bank_3">
                  Contacto 3 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="contact_bank_3" class="collapse">
              <div class="row">
                <label class="col-md-2 col-form-label">Banco</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="bank_3">
                      <option value="">Seleccionar</option>
                      @foreach(\App\Helpers::getListsBank() as $k => $v)
                      <option value="{{ $k }}" <?php if($provider->bank_3 == $k){ echo "selected='selected'"; } ?>>{{ $v }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">Tipo de Cuenta</label>
                  <div class="col-md-8">
                    <div class="form-group">
                      <select class="form-control selectpicker" data-style="btn btn-link" name="type_account_3">
                        @foreach(\App\Provider::getTipoCuenta() as $key => $val)
                        <option value="{{ $key }}" <?php if($provider->type_account_3 == $key){ echo "selected='selected'"; } ?>>{{ $val }}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">N* de Cuenta</label>
                <div class="col-md-8">
                    <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="number_account_3" value="{{ $provider->number_account_3 }}">
                    </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">CBU</label>
                <div class="col-md-8">
                    <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="cbu_3" value="{{ $provider->cbu_3 }}" minlength="22" maxlength="22">
                    </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">Alias</label>
                  <div class="col-md-8">
                    <div class="form-group">
                      <input class="form-control" type="text" name="alias_account_3" value="{{ $provider->alias_account_3 }}">
                    </div>
                </div>
              </div>
            </div>

            <div class="row p-2">
                <label class="col-md-12 col-form-label">
                    <center><strong>Seguros</strong></center>
                </label>
            </div>

            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#secure_1">
                  Seguro 1 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="secure_1" class="collapse show">
              <div class="row">
                <label class="col-md-2 col-form-label">Numero de Poliza</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="secure_number_poliza" value="{{ $provider->secure_number_poliza }}" maxlength="125">
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">Vencimiento</label>
                <div class="col-md-8">
                  <div class="form-group">
                    @if($provider->secure_expiration)
                      <input class="form-control datepicker" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="text" name="secure_expiration" value="{{ \App\Helpers::getDateParseFormat($provider->secure_expiration) }}" readonly>
                    @else
                      <input class="form-control datepicker" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="text" name="secure_expiration" value="{{ $provider->secure_expiration }}" readonly>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#secure_2">
                  Seguro 2 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="secure_2" class="collapse">
              <div class="row">
                <label class="col-md-2 col-form-label">Numero de Poliza</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="secure_number_poliza_2" value="{{ $provider->secure_number_poliza_2 }}" maxlength="125">
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">Vencimiento</label>
                <div class="col-md-8">
                  <div class="form-group">
                    @if($provider->secure_expiration_2)
                      <input class="form-control datepicker" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="text" name="secure_expiration_2" value="{{ \App\Helpers::getDateParseFormat($provider->secure_expiration_2) }}" readonly>
                    @else
                      <input class="form-control datepicker" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="text" name="secure_expiration_2" value="{{ $provider->secure_expiration_2 }}" readonly>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <div class="row p-2">
                <a class="col-md-2 col-form-label" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#secure_3">
                  Seguro 3 &#8595;
                </a>
                <div class="col-md-8"></div>
            </div>
            <div id="secure_3" class="collapse">
              <div class="row">
                <label class="col-md-2 col-form-label">Numero de Poliza</label>
                <div class="col-md-8">
                  <div class="form-group">
                    <input class="form-control" type="text" name="secure_number_poliza_3" value="{{ $provider->secure_number_poliza_3 }}" maxlength="125">
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-2 col-form-label">Vencimiento</label>
                <div class="col-md-8">
                  <div class="form-group">
                    @if($provider->secure_expiration_3)
                      <input class="form-control datepicker" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="text" name="secure_expiration_3" value="{{ \App\Helpers::getDateParseFormat($provider->secure_expiration_3) }}" readonly>
                    @else
                      <input class="form-control datepicker" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="text" name="secure_expiration_3" value="{{ $provider->secure_expiration_3 }}" readonly>
                    @endif
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
            <a href='{{ url("/providers") }}' class="back-history btn">Volver</a>
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