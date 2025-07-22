<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student as Aluno;
use App\Models\Orientador;
use App\Models\Company;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $tipo = $request->input('tipo');
        $senha = $request->input('senha');

        if ($tipo === 'aluno') {
            $user = Aluno::where('email', $request->input('email'))->first();
        } elseif ($tipo === 'orientador') {
            $user = Orientador::where('email', $request->input('email'))->first();
        } elseif ($tipo === 'empresa') {
            $user = Company::where('cnpj', $request->input('cnpj'))->first();
        } else {
            return response()->json(['message' => 'Tipo de usuário inválido'], 400);
        }

        if (!$user || !isset($user->senha) || !Hash::check($senha, $user->senha)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'user' => $user,
            'tipo' => $tipo
        ]);
    }
}
