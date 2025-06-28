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

//for now we are using web middleware to avoid cors issues for the api routes
//also were using session 
Route::prefix('tasks')->middleware('web', 'auth')->group(function () {
    Route::get('/', [ListTasksAction::class, 'asController']);
    Route::post('/', [CreateTaskAction::class, 'asController']);
    Route::put('/{task}', [UpdateTaskAction::class, 'asController']);
    Route::patch('/{task}', [UpdateTaskAction::class, 'asController']);
    Route::delete('/{task}', [DeleteTaskAction::class, 'asController']);
});
