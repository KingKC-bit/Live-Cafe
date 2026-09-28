<?php

namespace App\Http\Controllers\Running;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::latest()->get();
        return view('running.announcements', compact('announcements'));
    }

    public function show(Announcement $announcement): View
    {
        return view('running.announcements', compact('announcement'));
    }
}