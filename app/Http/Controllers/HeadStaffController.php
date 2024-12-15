<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeadStaffController extends Controller
{
    public function index()
    {
        return view('head.index');
    }
}
