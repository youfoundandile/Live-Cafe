<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PartnershipController extends Controller
{
    // index method to return the view for partnerships

    public function index()
    {
        return view('admin.placeholder', ['title' => 'Partnerships']);
    }
}
