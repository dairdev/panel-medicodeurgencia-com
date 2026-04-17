<?php

Route::group(['domain' => 'libro.medicodeurgencia.com'], function(){
    Route::get('/', 'WebController@index');
    Route::get('/acceso', 'WebController@acceso')->name('acceso');
    Route::post('/acceso', 'WebController@getAcceso')->name('post.acceso');

    Route::group(['middleware' => 'code'], function () {
	    Route::get('/especialidades', 'WebController@especialidades')->name('especialidades');
	    Route::get('/capitulos/{type}/{especialidad?}', 'WebController@capitulos')->name('capitulos');
	    Route::get('/subcapitulos/{type}/{capitulo}', 'WebController@subcapitulos')->name('subcapitulos');
	    Route::get('/ver-capitulo/{type}/{capitulo}', 'WebController@verCapitulo')->name('ver-capitulo');
	    Route::get('/ver-subcapitulo/{type}/{capitulo}', 'WebController@verSubCapitulo')->name('ver-subcapitulo');
	    Route::get('/descargar/{type}/{id}', 'WebController@descargar')->name('descargar');
	    Route::get('/farmacos/{txtfarmaco?}', 'WebController@farmacos')->name('farmacos');
	    Route::get('/farmaco/{cod_nacion}', 'WebController@farmaco')->name('farmaco');
	    Route::get('/informacion', 'WebController@informacion')->name('informacion');
	    Route::get('/informacion/mi-cuenta', 'WebController@miCuenta')->name('mi-cuenta');
	    Route::post('/informacion/mi-cuenta', 'WebController@postMiCuenta')->name('mi-cuenta-save');
	    Route::get('/informacion/contacto', 'WebController@contacto')->name('contacto');
	    Route::post('/informacion/contacto', 'WebController@postContacto')->name('contacto-save');
	    Route::get('/informacion/autores', 'WebController@autores')->name('autores');
	    Route::get('/informacion/pagina/{pagina}', 'WebController@pagina')->name('pagina');
	    Route::get('/informacion/cerrar-sesion', 'WebController@cerrarSesion')->name('cerrar-sesion');
	});
});

Route::post('/medicines/api-search', 'MedicinesController@searchShow');
Route::get('/medicines/api-search/{text?}', 'MedicinesController@searchShowGet');
Route::get('/medicines/api-medicine/{cod_nacion}', 'MedicinesController@medicineShow');
