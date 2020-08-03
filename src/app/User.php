<?php

namespace App;

use App\Notifications\MyResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use App\Helpers;
use App\UserFile;
use App\Client;
use App\Provider;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
      return "{$this->last_name} {$this->name}";
    }
    
    static function getFullName($id)
    {
      $user = User::find($id);
      
      return ($user ? $user->last_name.' '.$user->name : '-');
    }

    static function getActive($id)
    {
      $user = User::find($id);
      if($user) {
        return Helpers::getActive($user->is_active);
      }
    }

    static public function uploadFiles(Request $request, $nameField, $user_id, $mandatory = false, $maxUpload = '5120')
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
              $file->move(public_path("documents/users/$user_id"), $fileName);

              $media              = new UserFile;
              $media->user_id     = $user_id;
              $media->path        = "documents/users/$user_id/$fileName";
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

    public function sendPasswordResetNotification($token)
    {
      $this->notify(new MyResetPassword($token));
    }

    public function getRole()
    {
      if($this->permissions)
      {
        $roles = static::getRoles();
        return $roles[$this->permissions];
      }
    }

    public function getIDClient()
    {
      $id = null;

      if((bool) $this->is_client) {
        $client = Client::select('id')->where('id_user', $this->id)->first();
        $id       = $client->id;
      }

      return $id;
    }

    public function getIDProvider()
    {
      $id = null;

      if((bool) $this->is_provider)
      {
        $provider = Provider::select('id')->where('id_user', $this->id)->first();
        $id       = $provider->id;
      }

      return $id;
    }

    static public function getRoles()
    {
      $roles = [
        'admin' => 'Administrador',
        'arq'   => 'Arq',
        'junior'=> 'Junior'
      ];

      return $roles;
    }

    static public function getPermission($id)
    {
      $user = User::find($id);
      if($user) {
        return $user->permissions;
      }
    }
}
