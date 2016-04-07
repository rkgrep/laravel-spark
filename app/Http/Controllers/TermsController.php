<?php

namespace App\Http\Controllers;

use Parsedown;
use Illuminate\Routing\Controller;

class TermsController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $terms = (new Parsedown)->text(file_get_contents(base_path('terms.md')));

        return view('terms', compact('terms'));
    }
}
