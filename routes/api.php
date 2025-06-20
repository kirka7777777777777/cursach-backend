<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ProfileController;

Route::post('register', [AuthController::class, 'register']);
Route::post('register/manager', [AuthController::class, 'registerManager']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/login', function () {
    return response()->json(['message' => 'Требуется авторизация'], 401);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::delete('delete-account', [AuthController::class, 'deleteAccount']);
    Route::apiResource('cards', CardController::class);

    Route::get('/check-auth', function (Request $request) {
        return [
            'user' => $request->user(),
            'roles' => $request->user()->roles->pluck('name'),
            'permissions' => [
                'can_create_cards' => $request->user()->hasRole(['admin', 'manager']),
                'can_edit_all_cards' => $request->user()->hasRole('admin'),
            ]
        ];
    });
});
Route::middleware('auth:sanctum')->get('/user/roles', function (Request $request) {
    return [
        'roles' => $request->user()->roles->pluck('name')->toArray()
    ];
});
Route::middleware('auth:sanctum')->group(function () {
    // ...
    Route::get('/user/stats', [ProfileController::class, 'stats']);
    Route::put('/user/profile', [ProfileController::class, 'update']);
});
