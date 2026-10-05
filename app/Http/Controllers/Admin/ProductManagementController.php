<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ProductManagementController extends Controller
{
    // index method to return the view for products

    public function index()
    {
        return view('admin.placeholder', ['title' => 'Products']);
    }
}
