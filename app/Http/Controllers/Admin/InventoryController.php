<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    // index method to return the view for inventory

    public function index()
    {
        return view('admin.placeholder', ['title' => 'Inventory']);
    }
}
