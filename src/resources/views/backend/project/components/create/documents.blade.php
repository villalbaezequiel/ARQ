<div class="col-md-10">
    <div class="form-group">
        <div class="input-group-prepend">
        <span class="input-group-text">
            Link Google Drive<span class="required-red">*</span>
        </span>
        <input class="form-control" type="text" name="link_google_drive" value="{{ old('link_google_drive') }}" maxlength="255">
        </div>
    </div>
</div>

<div class="col-md-10">
    <div class="form-group">
        <div class="input-group-prepend">
        <span class="input-group-text">
            Link Dropbox<span class="required-red">*</span>
        </span>
        <input class="form-control" type="text" name="link_dropbox" value="{{ old('link_dropbox') }}" maxlength="255">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group text-center">
        <label class="btn" for="file_id">Seleccionar Documentos</label>
        <input type="file" name="files[]" id="file_id" onchange="javascript:arqJs.updateList()" multiple>
        </div>
    </div>
</div>

<div class="row hidden" id="name_files_content">
    <label class="col-md-2 col-form-label">Documentos</label>
    <div class="col-md-8">
        <div class="form-group" id="name_files_list">
        </div>
    </div>
</div>