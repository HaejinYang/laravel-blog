<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::resource('posts', PostController::class)->only(['index', 'show']); // 비로그인도 접근 가능
Route::resource('comments', CommentController::class);

// 포스트 안의 댓글 리스트 조회
Route::get('/posts/{post}/comments', [CommentController::class, 'indexByPost']);

// 인증
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

// 사용자 인증을 필요로하는 라우트
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('posts', PostController::class)->only(['store', 'update', 'destroy']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});
