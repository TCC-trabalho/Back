<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * GET /api/v1/projetos
     */
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects);
    }

    /**
     * GET /api/v1/projetos/{id}
     */
    public function show($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json([
                'message' => 'Projeto não encontrado'
            ], 404);
        }
        return response()->json($project);
    }

    /**
     * GET /api/v1/projetos/area/{area}
     */
    public function getByArea($area)
    {
        $projects = Project::where('area', $area)->get();
        return response()->json($projects);
    }

    /**
     * POST /api/v1/projetos
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descricao' => 'nullable|string',
            'area' => 'nullable|string|max:50',
            'data_criacao' => 'nullable|date',
            'status' => 'nullable|in:ativo,inativo',
            'objetivo' => 'nullable|string',
            'justificativa' => 'nullable|string',
            'senha_acesso' => 'nullable|string|max:255',
            'id_grupo' => 'required|integer|exists:grupo,id_grupo',
            'id_orientador' => 'required|integer|exists:orientador,id_orientador',
        ]);

        $project = Project::create($data);
        return response()->json($project, 201);
    }

    /**
     * PUT /api/v1/projetos/{id}
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }

        $data = $request->validate([
            'titulo' => 'sometimes|required|string|max:150',
            'descricao' => 'nullable|string',
            'area' => 'nullable|string|max:50',
            'data_criacao' => 'nullable|date',
            'status' => 'nullable|in:ativo,inativo',
            'objetivo' => 'nullable|string',
            'justificativa' => 'nullable|string',
            'senha_acesso' => 'nullable|string|max:255',
            'id_grupo' => 'sometimes|required|integer|exists:grupo,id_grupo',
            'id_orientador' => 'sometimes|required|integer|exists:orientador,id_orientador',
        ]);

        $project->update($data);
        return response()->json($project);
    }

    /**
     * DELETE /api/v1/projetos/{id}
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }

        $project->delete();
        return response()->json(null, 204);
    }
}
