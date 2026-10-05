<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class OrderManagementController extends Controller
{
    // index method to return the view for orders
    public function index()
    {
        return view('admin.placeholder', ['title' => 'Orders']);
    }
}
