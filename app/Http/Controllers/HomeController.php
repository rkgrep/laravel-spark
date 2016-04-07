<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the terms of service for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('home');
    }
}
