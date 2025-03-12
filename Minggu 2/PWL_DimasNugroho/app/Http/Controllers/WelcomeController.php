<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function hello() {
        return 'Hello World';
    }

    public function greeting() {
        return view('blog.hello') 
        -> with('name','Dimas')
        -> with('occupation', 'Student');
    }
}
