<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChecklistController extends Controller
{
    public function index()
    {
        $userId = request()->user_id;

        $checklists = DB::table('checklists')->where('user_id', $userId)->get();

        return response()->json($checklists);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'title' => 'required',
        ]);

        $user = DB::table('users')->where('id', $request->user_id)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $checklist = DB::table('checklists')->insertGetId([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['id' => $checklist, 'user_id' => $request->user_id, 'title' => $request->title], 201);
    }

    public function show($id)
    {
        $userId = request()->user_id; 

        $checklist = DB::table('checklists')->where('user_id', $userId)->where('id', $id)->first(); 

        if (!$checklist) {
            return response()->json(['message' => 'Checklist not found'], 404);
        }

        $items = DB::table('items')->where('checklist_id', $checklist->id)->get();

        $checklistData = [
            'id' => $checklist->id,
            'user_id' => $checklist->user_id,
            'title' => $checklist->title,
            'created_at' => $checklist->created_at,
            'updated_at' => $checklist->updated_at,
            'items' => $items,
        ];

        return response()->json($checklistData);
    }

    public function destroy($id)
    {
        $userId = request()->user_id; 

        $checklist = DB::table('checklists')->where('user_id', $userId)->where('id', $id)->first();

        if (!$checklist) {
            return response()->json(['message' => 'Checklist not found'], 404);
        }

        DB::table('checklists')->where('id', $id)->delete();

        return response()->json(['message' => 'Checklist deleted']);
    }
}
