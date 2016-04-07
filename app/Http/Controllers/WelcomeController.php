<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class WelcomeController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('welcome');
    }
}
