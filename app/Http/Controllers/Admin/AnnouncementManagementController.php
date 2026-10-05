<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AnnouncementManagementController extends Controller
{
    // index method to return the view for announcements

    public function index()
    {
        return view('admin.placeholder', ['title' => 'Announcements']);
    }
}
