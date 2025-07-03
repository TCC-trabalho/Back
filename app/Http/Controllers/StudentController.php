<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    /**
     * GET /api/v1/alunos
     */
    public function index()
    {
        $students = Student::all();
        return response()->json($students);
    }

    /**
     * POST /api/v1/alunos
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'cpf' => 'required|digits:11|unique:aluno,cpf',
            'rg' => 'nullable|string|max:20|unique:aluno,rg',
            'email' => 'required|email|max:150|unique:aluno,email',
            'nascimento' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'curso' => 'nullable|string|max:100',
            'inst_ensino' => 'nullable|string|max:150',
            'senha' => 'required|string|min:6|max:255',
        ]);

        // hash da senha
        $data['senha'] = bcrypt($data['senha']);

        $student = Student::create($data);
        return response()->json($student, 201);
    }

    /**
     * PUT /api/v1/alunos/{id}
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Aluno não encontrado'], 404);
        }

        $data = $request->validate([
            'nome' => 'sometimes|required|string|max:100',
            'cpf' => "sometimes|required|digits:11|unique:aluno,cpf,{$id},id_aluno",
            'rg' => "nullable|string|max:20|unique:aluno,rg,{$id},id_aluno",
            'email' => "sometimes|required|email|max:150|unique:aluno,email,{$id},id_aluno",
            'nascimento' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'curso' => 'nullable|string|max:100',
            'inst_ensino' => 'nullable|string|max:150',
            'senha' => 'nullable|string|min:6|max:255',
        ]);

        if (isset($data['senha'])) {
            $data['senha'] = bcrypt($data['senha']);
        }

        $student->update($data);
        return response()->json($student);
    }

    /**
     * DELETE /api/v1/alunos/{id}
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Aluno não encontrado'], 404);
        }

        $student->delete();
        return response()->json(null, 204);
    }

}
