<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\Group;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   return view('teste');
});
//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => 'auth',
'prefix' => 'clientes'

], function(
 
){ Route::get("/", 'ClientesController@listagem');
   Route::get("/{id}/editar", "ClientesController@formulario");
   Route::get("/novo", 'ClientesController@formulario');
   Route::post("/salvar", 'ClientesController@salvar');
   Route::delete("/{id}/excluir", "ClientesController@excluir");


});

Route::group(['middleware' => 'auth',
'prefix' => 'grupos'

], function(
 
){ Route::get("/", 'GruposController@listagem');
   Route::get("/{id}/editar", "GruposController@formulario");
   Route::get("/novo", 'GruposController@formulario');
   Route::post("/salvar", 'GruposController@salvar');
   Route::delete("/{id}/excluir", "GruposController@excluir");


});