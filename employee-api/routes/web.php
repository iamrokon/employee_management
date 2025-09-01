<?php

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redis::set('test_key', 'test_value');
    // dd(Redis::get('test_key'));
    return view('welcome');
});
