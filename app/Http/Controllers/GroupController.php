<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * GET /api/v1/grupos
     */
    public function index()
    {
        $groups = Group::all();
        return response()->json($groups);
    }

    /**
     * POST /api/v1/grupos
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'data_criacao' => 'nullable|date',
        ]);

        $group = Group::create($data);
        return response()->json($group, 201);
    }

    /**
     * PUT /api/v1/grupos/{id}
     */
    public function update(Request $request, $id)
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json(['message' => 'Grupo não encontrado'], 404);
        }

        $data = $request->validate([
            'nome' => 'sometimes|required|string|max:100',
            'descricao' => 'nullable|string',
            'data_criacao' => 'nullable|date',
        ]);

        $group->update($data);
        return response()->json($group);
    }

    /**
     * DELETE /api/v1/grupos/{id}
     */
    public function destroy($id)
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json(['message' => 'Grupo não encontrado'], 404);
        }

        $group->delete();
        return response()->json(null, 204);
    }

}
