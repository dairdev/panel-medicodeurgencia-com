<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Codigo;
use App\Models\Chapter;
use App\Models\Subchapter;
use App\Models\Specialty;
use App\Models\Upload;
use App\Models\Lectore;
use App\Models\Authore;
use App\Http\Requests;
use Mail;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/medicines/api-search', 'App\Http\Controllers\MedicinesController@searchShow');
 Route::get('/medicines/api-search/{text?}', 'App\Http\Controllers\MedicinesController@searchShowGet');
 Route::get('/medicines/api-medicine/{cod_nacion}', 'App\Http\Controllers\MedicinesController@medicineShow');

Route::group(['middleware' => ['token']], function () {
    Route::get('/colegiado/{num_colegiado}', 'App\Http\Controllers\ApiController@showColegiado');
    Route::post('/colegiado/{num_colegiado}', 'App\Http\Controllers\ApiController@updateColegiado');
    Route::post('/colegiado', 'App\Http\Controllers\ApiController@storeColegiado');
    Route::get('/colegiados', 'App\Http\Controllers\ApiController@indexColegiado');
    Route::get('/code/created/{start}/{end}', 'App\Http\Controllers\ApiController@indexCodes');
    Route::get('/code/{code}', 'App\Http\Controllers\ApiController@showCode');
  });

Route::get('/user/{code}', function ($code) {
    $data = [];
    try{
        $s = Codigo::select(['id', 'lectore_id', 'uses' ])->where('codigo', trim($code))->first();

        if($s == null){
            $data = [
                "success" => false,
                "message" => "Codigo no encontrado",
                "codigo" => $code
            ];
            return $data;
        }

        if ($s->uses == null || $s->uses == 0){
            $data = [
                "success" => true,
                "isNew" => true,
                "message" => "Uses is null"
            ];
            return $data;
        }
        
        /*if($s->uses == 2){
            $data = [
                "success" => false,
                "isNew" => false,
                "message" => "Máximo número (2) de usos de este código. Comuníquese con info@medicodeurgencia.com"
            ];
            return $data;
        }*/

        if($s->uses == 1){
            $data = [
                "success" => false,
                "isNew" => false,
                "message" => "Este código ya está siendo usado en otro dispositivo. Comuníquese con info@medicodeurgencia.com"
            ];
            return $data;
        }

        if($s->lectore_id != null){

            $user_id = $s->lectore_id;

            $user = Lectore::select('id', 'namo', 'surname', 'email', 'phone', 'password')->where('id', $user_id)->first();
            $codes = Codigo::select('codigo', 'date_compra', 'date_validez')->where('lectore_id', $user_id)->orderBy('date_validez', 'desc')->get();

            $data = [
                "success" => true,
                "isNew" => false,
                "uses" => $s->uses,
                "user" => $user,
                "codes" => $codes,
                "codigo" => $code,
                "message" => null
            ];
			
			 return $data;

            /*if($s->uses == 1){
                $c = Codigo::where('codigo', trim($code))->first();
                $c->uses = 2;
                $c->save();
            }*/

            if($s->uses < 0){
                $c = Codigo::where('codigo', trim($code))->first();
                $c->uses = 1;
                $c->save();
            }

		

        }else{

            $data = [
                "success" => false,
                "isNew" => true,
                "user" => [],
                "codes" => [],
                "message" => "Código no activo"
            ];
        }


    }catch(Exception $ex){
        $data = [ "success" => false, "message"=> $ex ];
    }
    return $data;
});

Route::post('/user-registration/{code}', 'App\Http\Controllers\LectoresController@saveAndActivateLector');

Route::get('/auth/signout/{code}', function ($code) {
    $s = Codigo::select([ 'lectore_id', 'uses' ])->where('codigo', $code)->first();
    $result = false;
    $message = "";
    if($s){
        $c = Codigo::where('codigo', trim($code))->first();
        $c->uses = $s->uses - 1;
        $c->save();
        $result = true;
    }else{
        $message = "Code no encontrado";
    }
    $data = [
        "success" => $result,
        'codes' => $s,
        'message' => $message
    ];
    return $data;
});
Route::get('/specialties', function () {
    $s = Specialty::select('id','name','image','orden')->orderBy('name', 'asc')->get();
    foreach ($s as $key => $t) {
        if(!empty($t['image'])){
            $img = Upload::find($t['image']);
            $s[$key]['image'] = $img->url();
        }else{
            $s[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
        }
    }
    return $s;
});

Route::get('/specialties/search/{text}', function ($text) {
    $s = Specialty::select('id','name','image','orden')->where('name', 'like', $text.'%')->orderBy('name', 'asc')->get();
    foreach ($s as $key => $t) {
        if(!empty($t['image'])){
            $img = Upload::find($t['image']);
            $s[$key]['image'] = $img->url();
        }else{
            $s[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
        }
    }
    return $s;
});


  Route::get('/chapters', function () {
    $s = Chapter::select(['id', 'name', 'image', 'orden', 'specialty_id', 'slug'])->orderBy('name', 'asc')->get();
    foreach ($s as $key => $t) {
        if(!empty($t['image'])){
          $img = Upload::find($t['image']);
          $s[$key]['image'] = $img->url();
        }else{
          $s[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
        }
      }
      return $s;
  });

  Route::get('/chapters/search/{text}', function ($text) {
    $s = Chapter::select(['chapters.id', 'chapters.name', 'chapters.image', 'chapters.orden', 'chapters.specialty_id', 'chapters.slug'])
      ->where([
        ['chapters.name', 'like', '%'.$text.'%']
      ])
      ->orderBy('name', 'asc')->get();
    foreach ($s as $key => $t) {
      if(!empty($t['image'])){
        $img = Upload::find($t['image']);
        $s[$key]['image'] = $img->url();
      }else{
        $s[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
      }

      $subc = Subchapter::where(['chapter_id' => $t->id])->orderBy('orden', 'asc')->get();
      $s[$key]['subchapters'] = false;
      if(count($subc) > 0){
        $s[$key]['subchapters'] = true; 
      }
      $s[$key]['detail_subchapter'] = false;
    }

    $su = Subchapter::select(['id', 'name', 'image', 'orden', 'chapter_id', 'slug'])
      ->where([
        ['name', 'like', '%'.$text.'%']
      ])
      ->orderBy('name', 'asc')->get();
    foreach ($su as $key => $v) {
      if(!empty($v['image'])){
        $img = Upload::find($v['image']);
        $su[$key]['image'] = $img->url();
      }else{
        $su[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
      }
      $su[$key]['subchapters'] = false;
      $su[$key]['detail_subchapter'] = true;
    }

    $s = $s->merge($su);

    return $s;
  });

  Route::get('/chapters/{id}/search/{text}', function ($id, $text) {
    $s = Chapter::select(['id', 'name', 'image', 'orden', 'specialty_id', 'slug'])
      ->where([
        ['specialty_id', '=', $id],
        ['name', 'like', '%'.$text.'%']
      ])->orderBy('orden', 'desc')->get();
    foreach ($s as $key => $t) {
      if(!empty($t['image'])){
        $img = Upload::find($t['image']);
        $s[$key]['image'] = $img->url();
      }else{
        $s[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
      }

      $subc = Subchapter::where(['chapter_id' => $t->id])->orderBy('orden', 'asc')->get();
      $s[$key]['subchapters'] = false;
      if(count($subc) > 0){
        $s[$key]['subchapters'] = true; 
      }
    }

    $su = Subchapter::select(['id', 'name', 'image', 'orden', 'chapter_id', 'slug'])
      ->whereHas('chapter', function($query) use ($id){
        $query->where('chapters.specialty_id', $id);
      })
      ->where([
        ['name', 'like', '%'.$text.'%']
      ])
      ->orderBy('name', 'asc')->get();
    foreach ($su as $key => $v) {
      if(!empty($v['image'])){
        $img = Upload::find($v['image']);
        $su[$key]['image'] = $img->url();
      }else{
        $su[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
      }
      $su[$key]['subchapters'] = false;
      $su[$key]['detail_subchapter'] = true;
    }

    $s = $s->merge($su);


    return $s;
  });

  Route::get('/chapters/{id}', function ($id) {
    $s = Chapter::select(['id', 'name', 'image', 'orden', 'specialty_id', 'slug'])->where('specialty_id', $id)->orderBy('orden', 'asc')->get();
    foreach ($s as $key => $t) {
      if(!empty($t['image'])){
        $img = Upload::find($t['image']);
        $s[$key]['image'] = $img->url();
      }else{
        $s[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
      }

      $subc = Subchapter::where(['chapter_id' => $t->id])->orderBy('orden', 'asc')->get();
      $s[$key]['subchapters'] = false;
      if(count($subc) > 0){
        $s[$key]['subchapters'] = true; 
      }
    }
    return $s;
  });

  Route::get('/subchapters/{id}/search/{text}', function ($id, $text) {
    $s = Subchapter::select(['id', 'image', 'name', 'orden', 'slug'])
      ->where([
        ['chapter_id', '=', $id],
        ['name', 'like', '%'.$text.'%']
      ])->orderBy('orden', 'asc')->get();
    foreach ($s as $key => $t) {
      if(!empty($t['image'])){
        $img = Upload::find($t['image']);
        $s[$key]['image'] = $img->url();
      }else{
        $s[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
      }
    }
    return $s;
  });

  Route::get('/subchapters/{id}', function ($id) {
    $s = Subchapter::select(['id', 'image', 'name', 'orden', 'slug'])->where('chapter_id', $id)->orderBy('orden', 'asc')->get();
    foreach ($s as $key => $t) {
      if(!empty($t['image'])){
        $img = Upload::find($t['image']);
        $s[$key]['image'] = $img->url();
      }else{
        $s[$key]['image'] = 'http://panel.medicodeurgencia.com/images/circle-512.png';
      }
    }
    return $s;
  });

  Route::get('/authors', function () {
    return Authore::orderBy('name', 'asc')->get();
  });

  Route::get('/email-login/{email}/{password}', function ($email, $password) {
    $s = Lectore::select(['id', 'password'])
    ->where([
      ['email', '=', $email]
    ])->first();

    if($s == null){
      $data = [
        'message'=> 'Usuario o Contraseña equivocada.'
      ];

      return $data;
    }

    //if(!password_verify($password, $s->password)){
    if($password != $s->password){
      $data = [
        'message'=> 'Clave errada'
      ];

      return $data;
    }

    $user_id = $s->id;

    if($user_id){
      $user = Lectore::select('id', 'namo', 'surname', 'email', 'phone', 'password')->where('id', $user_id)->first();
      $codes = Codigo::select('codigo', 'date_compra', 'date_validez')->where('lectore_id', $user_id)->orderBy('date_validez', 'desc')->get();

      $data = [
        'user' => $user,
        'codes' => $codes
      ];  
    }else{
      $data = [
        'user' => [],
        'codes' => []
      ];  
    }
    
    if(!empty($data['codes'])){
      $nowTime = Carbon\Carbon::now();
      foreach ($data['codes'] as $key => $code) {
        $data['codes'][$key]['validez'] = $code->date_validez->format('d/m/Y'); 
        $data['codes'][$key]['compra'] = $code->date_compra->format('d/m/Y'); 
        if($nowTime->gt($code->date_validez)){
          $data['codes'][$key]['estado'] = 'C';
        }else{
          $data['codes'][$key]['estado'] = 'V';
        }
      }
    }

    return $data;
  });
  Route::post('/user-save/{userId}', 'App\Http\Controllers\LectoresController@saveLector');

  Route::get('/access-code/{code}', function ($code) {
    $s = Codigo::where('codigo', $code)->where(function ($q) {
      $q->where('date_validez', '>=', date('Y-m-d'))->orWhereNull('date_validez');
    })->first();

    if($s){
      if(empty($s->date_validez)){
        $s->date_validez = date("Y-m-d", strtotime('+ '.$s->type.' days'));
        $s->save();
      }
    }
    
    return $s;
  });

  Route::post('/comment/{token}', function (Request $request, $token) {
    $s = Codigo::select('lectore_id')->where('codigo', $token)->first();
    $user = Lectore::select('id', 'namo', 'surname', 'email', 'phone')->where('id', $s->lectore_id)->first();

    $to = "info@medicodeurgencia.com";
    $subject = "Sugerencia de usuario sobre Mobile App Medico de Urgencias";
    $headers = "From:" . $user->email;
    $comment = $user->namo.' '.$user->surname."\n".$user->email."\n".$user->phone."\n";
    $comment .= $request->input('comment');
    
    mail($to,$subject,$comment, $headers);
    
    return ['success' => true];
  });

  Route::post('/forgot-password', function (Request $request) {
    $email = $request->input('email');

    //Check if password exists
    $user = Lectore::select('id', 'namo', 'surname', 'email', 'phone' )->where('email', $email)->first();

    if(!$user){
      return ['success' => false, "message" => "Email no encontrado"];
    }

    $codes = Codigo::select('codigo', 'date_compra', 'date_validez')->where('lectore_id', $user->id)->orderBy('date_validez', 'desc')->get();

    if(!$codes){
      return ['success' => false, "message" => "No tiene códigos vigentes"];
    }

    $nowTime = Carbon\Carbon::now();
    if($nowTime->gt($codes[0]->date_validez)){
      return ['success' => false, "message" => "No tiene códigos vigentes"];
    }

    $to = $email;
    $subject = "MedicoDeUrgencia: Recuperación de Contraseña";
    $headers = "From:info@medicodeurgencia.com";
    $comment = "Saludos, su contraseña ha sido restablecida a la siguiente: ". $codes[0]->codigo . "<br /> Por favor use este código para ingresar a la aplicación.";
    
    //mail($to,$subject,$comment, $headers);
    
    $to_name = $user->namo . " " . $user->surname;
    $to_email = $email;
    //$data = array('codigo' => $codes[0], 'lector' => $user);
    $data = array('codigos' => $codes, 'lector' => $user);

    Mail::send('emails.forgotpassword', $data, function($message) use ($to_name, $to_email) {
      $message->to($to_email, $to_name)->subject('Restauración de Constraseña');
      $message->from(env('MAIL_USERNAME'),'Médico de Urgencias');
    });

    return ['success' => true, 'email'=> $email, 'codigo' => $codes[0]->codigo];

  });

  Route::get('/chapters/{id}/download-pdf', 'App\Http\Controllers\LA\ChaptersController@downloadPdf');
  Route::get('/subchapters/{id}/download-pdf', 'App\Http\Controllers\LA\SubchaptersController@downloadPdf');

