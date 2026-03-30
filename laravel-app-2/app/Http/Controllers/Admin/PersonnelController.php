<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personnel;

class PersonnelController extends Controller
{
    public function index()
    {
        $personnel = Personnel::with('user')->get();
        return view('admin.personnel.index', compact('personnel'));
    }
}
