<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * @group Auth endpoints
 */
class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }



    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request)
    {
        $filedata = $request->file;
        $fileName = $request->file_real_name;
        $credentialType = $request->credentialType;
        $userId = $request->userId;
        try {
            $filearray = explode(';', $filedata);
            $filetype = explode(':', $filearray[0])[1];
            $extention = explode('/', $filetype);
            $delimiters = array(":", ";", ",");
            $ready = str_replace($delimiters, $delimiters[0], $filedata);
            $explodedSrc = explode($delimiters[0], $ready);
            $imageData = base64_decode(preg_replace('#^data:' . $extention[0] . '/\w+;base64,#i', '', $filedata));
            $file_type = preg_replace('#^' . $extention[0] . '/#i', '', $explodedSrc[1]);
            $current_time = Carbon::now();
            $filename = md5($current_time) . '.' . $file_type;
            $path =  $filename;
            if (Storage::disk('public_uploads')->put($path, $imageData)) {
                DB::table('credentials')->insert([
                    'user_id' => $userId,
                    'img_path' => '/uploads/'.$path,
                    'img_real_name' => $fileName,
                    'credential_type' => $credentialType,
                    'created_at' => Carbon::now()->toDateTimeString()
                  ]);
            }
            $data = file_get_contents(public_path()."/uploads/".$path);
            $base64 = 'data:image/' . $file_type . ';base64,' . base64_encode($data);
            return $base64;
        } catch (Exception $exc) {
            return json_encode(array('status' => false));
        }
    }

    public function getImages($credentialType = null , $userId = null) {
        try {
            $data = DB::table('credentials')->select('*')->where('credential_type', $credentialType)->where('user_id', $userId)->get();
            return json_encode(['status' => true, 'data' => $data]);
        }  catch (Exception $exc) {
            return json_encode(array('status' => false));
        }
    }

    public function getAllImages() {
        try {
            $data = DB::table('credentials')->join('users', 'users.id', '=', 'credentials.user_id')->select('users.name','credentials.*')->orderBy('user_id')->orderBy('credential_type')->get();
            return json_encode(['status' => true, 'data' => $data]);
        }  catch (Exception $exc) {
            return json_encode(array('status' => false));
        }
    }


    public function deleteImg(Request $request)
    {
      $id = $request->id;
      $img = DB::table('credentials')->select('img_path')->where('id', $id)->first();
      if (File::exists(public_path($img->img_path))){
        File::delete(public_path($img->img_path));
        $result = DB::table('credentials')->where('id', $id)->delete();
        return json_encode(array('status' => true));
      }
    }
  
}
