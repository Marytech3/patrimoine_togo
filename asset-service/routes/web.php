<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $index = public_path('index.html');

    return is_file($index) ? response()->file($index) : view('welcome');
});
