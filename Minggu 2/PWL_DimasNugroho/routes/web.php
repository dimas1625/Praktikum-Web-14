<?php

use Illuminate\Support\Facades\Route;

Route::get('/greeting', function () {
        return view('blog.hello', ['name' => 'Dimas']);
});
