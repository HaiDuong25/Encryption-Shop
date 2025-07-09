<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class DashboardController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
