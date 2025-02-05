<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Support\Facades\DB;

class ChecklistItemController extends Controller
{
    public function store(Request $request, $checklistId)
    {
        $userId = request()->user_id;

        $checklist = DB::table('checklists')->where('user_id', $userId)->where('id', $checklistId)->first();

        if (!$checklist) {
            return response()->json(['message' => 'Checklist not found'], 404);
        }

        $request->validate(['content' => 'required|string']);

        $itemId = DB::table('checklist_items')->insertGetId([
            'checklist_id' => $checklistId,
            'content' => $request->content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $item = DB::table('checklist_items')->where('id', $itemId)->first();

        return response()->json($item, 201);
    }

    public function show($checklistId, $itemId)
    {
        $item = DB::table('checklist_items')->where('checklist_id', $checklistId)->where('id', $itemId)->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json($item);
    }

    public function update(Request $request, $checklistId, $itemId)
    {
        $item = DB::table('checklist_items')->where('checklist_id', $checklistId)->where('id', $itemId)->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        DB::table('checklist_items')
            ->where('id', $itemId)
            ->update(['content' => $request->content, 'updated_at' => now()]);

        $updatedItem = DB::table('checklist_items')->where('id', $itemId)->first();

        return response()->json($updatedItem);
    }

    public function toggleStatus($checklistId, $itemId)
    {
        $item = DB::table('checklist_items')->where('checklist_id', $checklistId)->where('id', $itemId)->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $newStatus = !$item->is_completed;

        DB::table('checklist_items')
            ->where('id', $itemId)
            ->update(['is_completed' => $newStatus, 'updated_at' => now()]);

        $updatedItem = DB::table('checklist_items')->where('id', $itemId)->first();

        return response()->json($updatedItem);
    }

    public function destroy($checklistId, $itemId)
    {
        $item = DB::table('checklist_items')->where('checklist_id', $checklistId)->where('id', $itemId)->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        DB::table('checklist_items')->where('id', $itemId)->delete();

        return response()->json(['message' => 'Item deleted']);
    }
}
