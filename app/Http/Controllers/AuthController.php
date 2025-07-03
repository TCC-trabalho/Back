<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * POST /api/v1/login
     * Corpo: email + senha
     * Consulta nas tabelas: aluno, empresa e orientador
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string'
        ]);

        // Tenta autenticar como Aluno
        $aluno = DB::table('aluno')->where('email', $data['email'])->first();
        if ($aluno && Hash::check($data['senha'], $aluno->senha)) {
            return response()->json([
                'message' => 'Login realizado com sucesso (Aluno)',
                'tipo' => 'aluno',
                'user' => $aluno
            ]);
        }

        // Tenta autenticar como Orientador
        $orientador = DB::table('orientador')->where('email', $data['email'])->first();
        if ($orientador && Hash::check($data['senha'], $orientador->senha)) {
            return response()->json([
                'message' => 'Login realizado com sucesso (Orientador)',
                'tipo' => 'orientador',
                'user' => $orientador
            ]);
        }

        // Tenta autenticar como Empresa
        $empresa = DB::table('empresa')->where('email', $data['email'])->first();
        if ($empresa && Hash::check($data['senha'], $empresa->senha)) {
            return response()->json([
                'message' => 'Login realizado com sucesso (Empresa)',
                'tipo' => 'empresa',
                'user' => $empresa
            ]);
        }

        return response()->json([
            'message' => 'Credenciais inválidas'
        ], 401);
    }
}
