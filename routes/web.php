<?php

use App\Http\Controllers\PostController as PostControllerAlias;
use Illuminate\Support\Facades\Route;

Route::resource('posts', PostControllerAlias::class);
