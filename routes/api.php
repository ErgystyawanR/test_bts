<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ChecklistItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post('/checklists', [ChecklistController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/checklists/{id}', [ChecklistController::class, 'destroy']);

Route::get('/checklists', [ChecklistController::class, 'index']);
Route::get('/checklists/{id}', [ChecklistController::class, 'show']);
Route::post('/checklists/{checklist}/items', [ChecklistItemController::class, 'store']);
Route::get('/checklists/{checklist}/items/{item}', [ChecklistItemController::class, 'show']);
Route::put('/checklists/{checklist}/items/{item}', [ChecklistItemController::class, 'update']);
Route::patch('/checklists/{checklistId}/items/{itemId}/status', [ChecklistItemController::class, 'toggleStatus']);
Route::delete('/checklists/{checklist}/items/{item}', [ChecklistItemController::class, 'destroy']);
