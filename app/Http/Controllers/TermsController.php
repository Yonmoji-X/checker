<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index()
    {
        return view('terms.index'); // または view('terms.index') に変更
    }
}
