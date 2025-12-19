<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User as ModelsUser;

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação (Sanctum)
|--------------------------------------------------------------------------
*/

// Registrar novo usuário
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    $user = ModelsUser::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ], 201);
});

// Login
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = ModelsUser::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Credenciais inválidas'
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ]);
});

// Logout
Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout realizado com sucesso'
    ]);
})->middleware('auth:sanctum');

// Verificar usuário autenticado
Route::get('/me', function (Request $request) {
    return response()->json($request->user());
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Rota de teste
|--------------------------------------------------------------------------
*/

Route::get('/ping', function () {
    return response()->json([
        'message' => 'pong',
        'timestamp' => now()
    ]);
});

/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS (sem autenticação)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // EVENTOS
    Route::prefix('eventos')->group(function () {
        Route::get('/', 'Api\EventoApiController@index');
        Route::get('/proximos', 'Api\EventoApiController@proximos');
        Route::get('/disponiveis', 'Api\EventoApiController@disponiveis');
        Route::get('/estatisticas', 'Api\EventoApiController@estatisticas');
        Route::get('/{id}', 'Api\EventoApiController@show');
    });

    // LOCAIS
    Route::prefix('locais')->group(function () {
        Route::get('/', 'Api\LocalApiController@index');
        Route::get('/cidades', 'Api\LocalApiController@cidades');
        Route::get('/estatisticas', 'Api\LocalApiController@estatisticas');
        Route::get('/{id}', 'Api\LocalApiController@show');
    });

    // PARTICIPANTES
    Route::prefix('participantes')->group(function () {
        Route::get('/', 'Api\ParticipanteApiController@index');
        Route::get('/cpf/{cpf}', 'Api\ParticipanteApiController@buscarPorCpf');
        Route::get('/{id}', 'Api\ParticipanteApiController@show');
    });

    // INGRESSOS
    Route::prefix('ingressos')->group(function () {
        Route::get('/', 'Api\IngressoApiController@index');
        Route::get('/evento/{evento_id}', 'Api\IngressoApiController@porEvento');
        Route::get('/evento/{evento_id}/disponiveis', 'Api\IngressoApiController@disponiveisPorEvento');
        Route::get('/{id}', 'Api\IngressoApiController@show');
    });
//Inscricoes
    Route::prefix('inscricoes')->group(function () {
        Route::get('/', 'Api\InscricaoApiController@index');
        Route::get('/estatisticas', 'Api\InscricaoApiController@estatisticas');
        Route::get('/codigo/{codigo}', 'Api\InscricaoApiController@buscarPorCodigo');
        Route::get('/evento/{evento_id}', 'Api\InscricaoApiController@porEvento');
        Route::get('/evento/{evento_id}/estatisticas', 'Api\InscricaoApiController@estatisticasPorEvento');
        Route::get('/participante/{participante_id}', 'Api\InscricaoApiController@porParticipante');
        Route::get('/{id}', 'Api\InscricaoApiController@show');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', 'Api\UserApiController@index'); });
});

/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS (com autenticação Sanctum)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // EVENTOS - CRUD
    Route::prefix('eventos')->group(function () {
        Route::post('/', 'Api\EventoApiController@store');
        Route::put('/{id}', 'Api\EventoApiController@update');
        Route::delete('/{id}', 'Api\EventoApiController@destroy');
    });

    // LOCAIS - CRUD
    Route::prefix('locais')->group(function () {
        Route::post('/', 'Api\LocalApiController@store');
        Route::put('/{id}', 'Api\LocalApiController@update');
        Route::delete('/{id}', 'Api\LocalApiController@destroy');
    });

    // PARTICIPANTES - CRUD
    Route::prefix('participantes')->group(function () {
        Route::post('/', 'Api\ParticipanteApiController@store');
        Route::put('/{id}', 'Api\ParticipanteApiController@update');
        Route::delete('/{id}', 'Api\ParticipanteApiController@destroy');
        Route::get('/estatisticas', 'Api\ParticipanteApiController@estatisticas');
    });

    // INGRESSOS - CRUD
    Route::prefix('ingressos')->group(function () {
        Route::post('/', 'Api\IngressoApiController@store');
        Route::put('/{id}', 'Api\IngressoApiController@update');
        Route::delete('/{id}', 'Api\IngressoApiController@destroy');
        Route::get('/estatisticas', 'Api\IngressoApiController@estatisticas');
    });
     // inscricoes - CRUD e perfil (autenticado)
    
    Route::prefix('inscricoes')->group(function () {
        Route::post('/', 'Api\InscricaoApiController@store');
        Route::put('/{id}', 'Api\InscricaoApiController@update');
        Route::delete('/{id}', 'Api\InscricaoApiController@destroy');
        
        // Ações especiais
        Route::put('/{id}/confirmar', 'Api\InscricaoApiController@confirmar');
        Route::put('/{id}/cancelar', 'Api\InscricaoApiController@cancelar');
        
        // Minhas inscrições
        Route::get('/minhas', 'Api\InscricaoApiController@minhas');
    });




    // USUÁRIOS - CRUD e perfil (autenticado)
    Route::prefix('users')->group(function () {
        Route::post('/', 'Api\UserApiController@store');
        Route::put('/{id}', 'Api\UserApiController@update');
        Route::delete('/{id}', 'Api\UserApiController@destroy');
        
        // Perfil do usuário logado
        Route::get('/me', 'Api\UserApiController@me');
        Route::put('/me/password', 'Api\UserApiController@updatePassword');
    });
});