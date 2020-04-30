@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">{{ __('Register') }}</div>
          <div class="card-body">
            {!! Form::open(['route' => 'providers.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

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

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Nombre o Razon Social <span class="required-red">*</span></label>
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" maxlength="125" required>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">CUIT <span class="required-red">*</span></label>
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="number" name="cuit" value="{{ old('cuit') }}" maxlength="125" required>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Rubro <span class="required-red">*</span></label>
                <div class="col-md-6">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" name="id_category" required>
                      <option value="">Seleccionar</option>
                      <option value="1">Iluminista</option>
                      <option value="2">Electricista</option>
                      <option value="3">Plomero</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Pais</label>
                <div class="col-md-2">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="id_country">
                      @foreach(\App\Helpers::getCountries() as $key => $val)
                        <option value="{{ $key }}">{{ $val }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" data-live-search="true" name="id_province">
                      @foreach(\App\Helpers::getProvinces() as $key => $val)
                        <option value="{{ $key }}">{{ $val }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <input class="form-control" type="text" name="location" value="{{ old('location') }}" placeholder="Localidad">
                  </div>
                </div>
              </div>
            
              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Dirección</label>
                <div class="col-md-3">
                  <div class="form-group">
                    <input class="form-control" type="text" name="address" value="{{ old('address') }}">
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="address_number" value="{{ old('address_number') }}" placeholder="Número">
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <input class="form-control" type="text" name="piso_depto" value="{{ old('piso_depto') }}" placeholder="Piso/Depto">
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="text" name="cp" value="{{ old('cp') }}" placeholder="CP">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Condicion de IVA</label>
                <div class="col-md-6">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" name="condition_iva">
                      <option value="0">No</option>
                      <option value="1">Si</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Retiene Ingresos brutos</label>
                <div class="col-md-6">
                  <div class="form-group">
                    <select class="form-control selectpicker" data-style="btn btn-link" name="retains_gross_income">
                      <option value="0">No</option>
                      <option value="1">Si</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">% Retiene Ingresos brutos</label>
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control input-type-number" type="number" name="retains_gross_income_porcentage" value="{{ old('retains_gross_income_porcentage') }}" maxlength="125">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Tipo de factura <span class="required-red">*</span></label>
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control" type="text" name="invoice_type" value="{{ old('invoice_type') }}" maxlength="125" required>
                  </div>
                </div>
              </div>

              <input type="hidden" name="is_active" value="0">

              <div class="row p-2">
                  <label class="col-md-12 col-form-label">
                      <center><strong>Datos de Contacto</strong></center>
                  </label>
              </div>

              <div class="row p-2">
                  <a class="col-md-4 col-form-label text-md-right" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_1">
                    Contacto 1 &#8595;
                  </a>
                  <div class="col-md-6"></div>
              </div>
              <div id="contact_1" class="collapse show">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Nombre <span class="required-red">*</span></label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="contact_name" value="{{ old('contact_name') }}" maxlength="125" required>
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Cargo <span class="required-red">*</span></label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="contact_cargo" value="{{ old('contact_cargo') }}" maxlength="125" required>
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Telefono <span class="required-red">*</span></label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="contact_telephone" value="{{ old('contact_telephone') }}" maxlength="125" required>
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Email <span class="required-red">*</span></label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="email" name="contact_email" value="{{ old('contact_email') }}" maxlength="125" required>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row p-2">
                  <a class="col-md-4 col-form-label text-md-right" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_2">
                    Contacto 2 &#8595;
                  </a>
                  <div class="col-md-6"></div>
              </div>
              <div id="contact_2" class="collapse">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Nombre</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="contact_name_2" value="{{ old('contact_name_2') }}" maxlength="125">
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Cargo</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="contact_cargo_2" value="{{ old('contact_cargo_2') }}" maxlength="125">
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Telefono</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="contact_telephone_2" value="{{ old('contact_telephone_2') }}" maxlength="125">
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Email</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="email" name="contact_email_2" value="{{ old('contact_email_2') }}" maxlength="125">
                    </div>
                  </div>
                </div>
              </div>

              <div class="row p-2">
                  <a class="col-md-4 col-form-label text-md-right" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_3">
                    Contacto 3 &#8595;
                  </a>
                  <div class="col-md-6"></div>
              </div>
              <div id="contact_3" class="collapse">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Nombre</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="contact_name_3" value="{{ old('contact_name_3') }}" maxlength="125">
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Cargo</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="contact_cargo_3" value="{{ old('contact_cargo_3') }}" maxlength="125">
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Telefono</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="contact_telephone_3" value="{{ old('contact_telephone_3') }}" maxlength="125">
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Email</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="email" name="contact_email_3" value="{{ old('contact_email_3') }}" maxlength="125">
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
                  <a class="col-md-4 col-form-label text-md-right" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_bank_1">
                    Contacto 1 &#8595;
                  </a>
                  <div class="col-md-6"></div>
              </div>
              <div id="contact_bank_1" class="collapse show">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Banco</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="bank">
                        <option value="">Seleccionar</option>
                        @foreach(\App\Helpers::getListsBank() as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Tipo de Cuenta</label>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control selectpicker" data-style="btn btn-link" name="type_account">
                          @foreach(\App\Provider::getTipoCuenta() as $key => $val)
                          <option value="{{ $key }}">{{ $val }}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">N* de Cuenta</label>
                  <div class="col-md-6">
                      <div class="form-group">
                      <input class="form-control input-type-number" type="text" name="number_account" value="{{ old('number_account') }}">
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">CBU</label>
                  <div class="col-md-6">
                      <div class="form-group">
                      <input class="form-control input-type-number" type="text" name="cbu" value="{{ old('cbu') }}" minlength="22" maxlength="22">
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Alias</label>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input class="form-control" type="text" name="alias_account" value="{{ old('alias_account') }}">
                      </div>
                  </div>
                </div>
              </div>

              <div class="row p-2">
                  <a class="col-md-4 col-form-label text-md-right" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_bank_2">
                    Contacto 2 &#8595;
                  </a>
                  <div class="col-md-6"></div>
              </div>
              <div id="contact_bank_2" class="collapse">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Banco</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="bank_2">
                        <option value="">Seleccionar</option>
                        @foreach(\App\Helpers::getListsBank() as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Tipo de Cuenta</label>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control selectpicker" data-style="btn btn-link" name="type_account_2">
                          @foreach(\App\Provider::getTipoCuenta() as $key => $val)
                          <option value="{{ $key }}">{{ $val }}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">N* de Cuenta</label>
                  <div class="col-md-6">
                      <div class="form-group">
                      <input class="form-control input-type-number" type="text" name="number_account_2" value="{{ old('number_account_2') }}">
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">CBU</label>
                  <div class="col-md-6">
                      <div class="form-group">
                      <input class="form-control input-type-number" type="text" name="cbu_2" value="{{ old('cbu_2') }}" minlength="22" maxlength="22">
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Alias</label>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input class="form-control" type="text" name="alias_account_2" value="{{ old('alias_account_2') }}">
                      </div>
                  </div>
                </div>
              </div>

              <div class="row p-2">
                  <a class="col-md-4 col-form-label text-md-right" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#contact_bank_3">
                    Contacto 3 &#8595;
                  </a>
                  <div class="col-md-6"></div>
              </div>
              <div id="contact_bank_3" class="collapse">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Banco</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <select class="form-control selectpicker" data-live-search="true" data-style="btn btn-link" name="bank_3">
                        <option value="">Seleccionar</option>
                        @foreach(\App\Helpers::getListsBank() as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Tipo de Cuenta</label>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select class="form-control selectpicker" data-style="btn btn-link" name="type_account_3">
                          @foreach(\App\Provider::getTipoCuenta() as $key => $val)
                          <option value="{{ $key }}">{{ $val }}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">N* de Cuenta</label>
                  <div class="col-md-6">
                      <div class="form-group">
                      <input class="form-control input-type-number" type="text" name="number_account_3" value="{{ old('number_account_3') }}">
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">CBU</label>
                  <div class="col-md-6">
                      <div class="form-group">
                      <input class="form-control input-type-number" type="text" name="cbu_3" value="{{ old('cbu_3') }}" minlength="22" maxlength="22">
                      </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Alias</label>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input class="form-control" type="text" name="alias_account_3" value="{{ old('alias_account_3') }}">
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
                  <a class="col-md-4 col-form-label text-md-right" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#secure_1">
                    Seguro 1 &#8595;
                  </a>
                  <div class="col-md-6"></div>
              </div>
              <div id="secure_1" class="collapse show">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Numero de Poliza</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="secure_number_poliza" value="{{ old('secure_number_poliza') }}" maxlength="125">
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Vencimiento</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="date" name="secure_expiration" value="{{ old('secure_expiration') }}">
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row p-2">
                  <a class="col-md-4 col-form-label text-md-right" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#secure_2">
                    Seguro 2 &#8595;
                  </a>
                  <div class="col-md-6"></div>
              </div>
              <div id="secure_2" class="collapse">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Numero de Poliza</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="secure_number_poliza_2" value="{{ old('secure_number_poliza_2') }}" maxlength="125">
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Vencimiento</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="date" name="secure_expiration_2" value="{{ old('secure_expiration_2') }}">
                    </div>
                  </div>
                </div>
              </div>

              <div class="row p-2">
                  <a class="col-md-4 col-form-label text-md-right" style="color: #aaa; font-weight: 600;" data-toggle="collapse" href="#secure_3">
                    Seguro 3 &#8595;
                  </a>
                  <div class="col-md-6"></div>
              </div>
              <div id="secure_3" class="collapse">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Numero de Poliza</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" type="text" name="secure_number_poliza_3" value="{{ old('secure_number_poliza_3') }}" maxlength="125">
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Vencimiento</label>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" style="background-color: white!important;" pattern="^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$" type="date" name="secure_expiration_3" value="{{ old('secure_expiration_3') }}">
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="padding: 15px;">
                  <label class="col-md-12 col-form-label">
                      <center><strong>Documentos</strong></center>
                  </label>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <div class="form-group text-center">
                    <label class="btn" for="file_id">Seleccionar Documentos</label>
                    <input type="file" name="files[]" id="file_id" multiple>
                  </div>
                </div>
              </div>

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <input type="hidden" name="create_provider_guest" value="1">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
              </div>
            {!! Form::close() !!}
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
