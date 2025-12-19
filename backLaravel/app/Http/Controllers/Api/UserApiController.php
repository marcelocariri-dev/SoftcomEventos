<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    /**
     * GET /api/v1/users
     * Listar todos os usuários
     */
    public function index(Request $request)
    {
        try {
            $query = User::query();

          

            if ($request->has('email')) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }

            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            $perPage = $request->input('per_page', 15);
            $users = $query->orderBy('name')->paginate($perPage);

            return UserResource::collection($users);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar usuários',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/users/{id}
     * Buscar usuário por ID
     */
    public function show($id)
    {
        try {
            $user = User::with(['participante', 'eventosOrganizados'])->find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'Usuário não encontrado'
                ], 404);
            }

            return new UserResource($user);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/v1/users
     * Criar usuário
     */
    public function store(UserRequest $request)
    {
        try {
            $dados = $request->validated();
            $dados['password'] = Hash::make($dados['password']);

            $user = User::create($dados);

            return response()->json([
                'message' => 'Usuário criado com sucesso',
                'data' => new UserResource($user)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/v1/users/{id}
     * Atualizar usuário
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'Usuário não encontrado'
                ], 404);
            }

            $dados = $request->validated();

            // Atualizar senha apenas se foi enviada
            if (!empty($dados['password'])) {
                $dados['password'] = Hash::make($dados['password']);
            } else {
                unset($dados['password']);
            }

            $user->update($dados);

            return response()->json([
                'message' => 'Usuário atualizado com sucesso',
                'data' => new UserResource($user)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/v1/users/{id}
     * Excluir usuário
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'Usuário não encontrado'
                ], 404);
            }

            // Verificar se tem eventos organizados
            if ($user->eventosOrganizados()->count() > 0) {
                return response()->json([
                    'message' => 'Não é possível excluir usuário com eventos organizados'
                ], 400);
            }

            $user->delete();

            return response()->json([
                'message' => 'Usuário excluído com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/users/me
     * Dados do usuário autenticado
     */
    public function me(Request $request)
    {
        try {
            $user = $request->user()->load(['participante', 'eventosOrganizados']);

            return new UserResource($user);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar dados do usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/v1/users/me/password
     * Trocar senha do usuário autenticado
     */
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ], [
                'current_password.required' => 'Senha atual é obrigatória',
                'new_password.required' => 'Nova senha é obrigatória',
                'new_password.min' => 'Nova senha deve ter no mínimo 8 caracteres',
                'new_password.confirmed' => 'Confirmação de senha não confere',
            ]);

            $user = $request->user();

            // Verificar senha atual
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Senha atual incorreta'
                ], 400);
            }

            // Atualizar senha
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'message' => 'Senha atualizada com sucesso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar senha',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}