<?php

use App\Controllers\PostController;
use Somecode\Framework\Routing\Route;

return [
    Route::get('/', [dHomeController::class, 'index']),
    Route::get('/posts/{id:\d+}', [PostController::class, 'show']),
];
