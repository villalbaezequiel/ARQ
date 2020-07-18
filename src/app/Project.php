<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Helpers;
use App\ProjectDocument;

class Project extends Model
{

    static public function getName($id)
    {
      $project = Project::find($id);
      
      if($project){
        return $project->name;
      }
    }

    static public function getDateParseFormat($date, $separartor = '-')
    {
      if($date) {
        $substrDate = substr($date, 0, 10);
        $format = explode('/', $substrDate);
        if($format[2]){
          return $format[2].$separartor.$format[1].$separartor.$format[0];
        } else {
          return "01".$separartor."01".$separartor."1990";
        }
      } else {
        return null;
      }
    }

    static public function uploadDocuments(Request $request, $nameField, $id_project, $mandatory = false, $maxUpload = '5120')
    {
      if($request)
      {
        try {

          $required = ($mandatory ? 'required' : '');

          $request->validate([
            $nameField => "$required|max:$maxUpload",
          ]);

          if($request->hasFile($nameField))
          {
            $files = $request->file($nameField);
            $count = 1;
            foreach($files as $file)
            {
              $fileName = date('Ymdhis').$count.'.'.$file->getClientOriginalExtension();
              $file->move(public_path("documents/projects/$id_project"), $fileName);

              $media              = new ProjectDocument;
              $media->id_project  = $id_project;
              $media->path        = "documents/projects/$id_project/$fileName";
              $media->path_label  = $file->getClientOriginalName();
              $media->save();

              $count++;
            }
          }
          return true;

        } catch(\Exception $error) {
          return false;
        }
      }
    }

    public function getIsOpenBudget()
    {
      return ((int) $this->is_open_budget);
    }

    public function setIsOpenBudget($val)
    {
      try {
        $this->is_open_budget = $val;
        $this->save();
         
        return true;
      } catch (\Exception $err) {
        return false;
      }
    }

    static public function isOpenBudget($idProject)
    {
      $project = Project::find($idProject);
      
      if($project){
        return ( (int) $project->is_open_budget ? true : false);
      }
    }

    static public function setParseFormatPrice($price)
    {
      return (float) str_replace(array("$", ","), array("", ""), $price);
    }

    static public function setParseFormatTax($tax)
    {
      return (float) str_replace("%", "", $tax);
    }
}
