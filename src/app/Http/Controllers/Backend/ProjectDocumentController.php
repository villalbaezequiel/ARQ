<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProjectDocument;

class ProjectDocumentController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $project_document = ProjectDocument::find($id);
      $project_document->delete();
      
      return back()->with('message', 'Documento eliminado con exito!');
    }
}
