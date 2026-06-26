<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index()
    {
        return view('admin.pages.home');
    }
}
