<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::resource('posts', PostController::class);
Route::resource('comments', CommentController::class);

// 포스트 안의 댓글 리스트 조회
Route::get('/posts/{post}/comments', [CommentController::class, 'indexByPost']);
