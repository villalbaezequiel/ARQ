@if(count($project_documents))
    @foreach($project_documents as $file)
    {!! Form::open(['route' => ['project_documents.destroy', $file->id], 'method' => 'DELETE', 'id' => "file_$file->id"]) !!}
    {!! Form::close() !!}
    @endforeach
@endif