<?php

use Somecode\Framework\Routing\Route;

return [
    Route::get('/', ['HomeController::class', 'index']),
];
