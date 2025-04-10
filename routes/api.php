<?php

use App\Http\Controllers\Api\TranslationController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('translations', TranslationController::class);
    Route::get('translation/search', [TranslationController::class, 'searchTranslations']);
    Route::get('/export-translations', [TranslationController::class, 'exportAsJSON']);
});

Route::get('/access-token', function() {
    $user = User::where('email', 'test@example.com')->first();
    if (!$user) {
        return response()->json(['message' => 'Test user not found.'], 404);
    }
    $token = $user->createToken('TestToken')->plainTextToken;
    return response()->json(['token' => $token]);
});
