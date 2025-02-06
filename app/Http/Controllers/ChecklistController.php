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
        $checklists = DB::table('checklists')->get();

        return response()->json($checklists);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $user = Auth::user();

        $checklist = DB::table('checklists')->insertGetId([
            'user_id' => $user->id, 
            'title' => $request->title,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['id' => $checklist, 'user_id' => $user->id, 'title' => $request->title], 201);
    }

    public function show($id)
    {
        $checklist = DB::table('checklists')->where('id', $id)->first();

        if (!$checklist) {
            return response()->json(['message' => 'Checklist not found'], 404);
        }

        $items = DB::table('checklist_items')->where('checklist_id', $checklist->id)->get();

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
        $checklist = DB::table('checklists')->where('id', $id)->first();

        if (!$checklist) {
            return response()->json(['message' => 'Checklist not found'], 404);
        }

        DB::table('checklists')->where('id', $id)->delete();

        return response()->json(['message' => 'Checklist deleted']);
    }
}
