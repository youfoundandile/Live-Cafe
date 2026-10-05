<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class EventManagementController extends Controller
{
    // index method to return the view for events

    public function index()
    {
        return view('admin.placeholder', ['title' => 'Events']);
    }
}
