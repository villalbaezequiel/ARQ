<div class="row">
    <label class="col-md-2 col-form-label">Link Google Drive</label>
    <div class="col-md-8">
        <div class="form-group">
        <input class="form-control" type="text" name="link_google_drive" value="{{ $project->link_google_drive }}" maxlength="255">
        </div>
    </div>
</div>

<div class="row">
    <label class="col-md-2 col-form-label">Link Dropbox</label>
    <div class="col-md-8">
        <div class="form-group">
        <input class="form-control" type="text" name="link_dropbox" value="{{ $project->link_dropbox }}" maxlength="255">
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

@if(count($project_documents))
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
        @foreach($project_documents as $file)
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