<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Task\CreateTaskAction;
use App\Actions\Task\UpdateTaskAction;
use App\Actions\Task\DeleteTaskAction;
use App\Actions\Task\ListTasksAction;
use App\Models\Task;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/tasks', [ListTasksAction::class, 'asController']);
    Route::post('/tasks', [CreateTaskAction::class, 'asController']);
    Route::put('/tasks/{task}', [UpdateTaskAction::class, 'asController']);
    Route::patch('/tasks/{task}', [UpdateTaskAction::class, 'asController']);
    Route::delete('/tasks/{task}', [DeleteTaskAction::class, 'asController']);
});
