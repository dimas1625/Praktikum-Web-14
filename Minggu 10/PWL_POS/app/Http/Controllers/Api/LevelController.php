<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LevelModel;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        // returns JSON array of all levels
        return LevelModel::all();
    }

    public function store(Request $request)
    {
        $level = LevelModel::create($request->all());
        return response()->json($level, 201);
    }

    public function show($id)
{
    $level = LevelModel::find($id);
    if (! $level) {
        return response()->json(['message' => 'Level not found'], 404);
    }
    return response()->json($level);
}
    public function update(Request $request, LevelModel $level)
    {
        $level->update($request->all());
        return $level;
    }

    public function destroy(LevelModel $level)
    {
        $level->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus'
        ]);
    }
}
