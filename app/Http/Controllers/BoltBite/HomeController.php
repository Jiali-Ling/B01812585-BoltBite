<?php

namespace App\Http\Controllers\BoltBite;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('boltbite.index', [
            'appName' => 'BoltBite'
        ]);
    }
}
