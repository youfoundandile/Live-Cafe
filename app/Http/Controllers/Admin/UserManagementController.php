<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UserManagementController extends Controller
{
    // index method to return the view for users

    public function index()
    {
        return view('admin.placeholder', ['title' => 'Users']);
    }
}
