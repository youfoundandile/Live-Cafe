<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SaleManagementController extends Controller
{
    // index method to return the view for sales

    public function index()
    {
        return view('admin.placeholder', ['title' => 'Sales']);

    }
}
