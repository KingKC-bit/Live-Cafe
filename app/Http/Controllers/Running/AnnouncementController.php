<?php

namespace App\Http\Controllers\Running;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()->published()->forDisplay()->get();

        return view('running.announcements', compact('announcements'));
    }

    public function show(Announcement $announcement): View
    {
        // Drafts stay private until an admin publishes them.
        abort_unless($announcement->isPublished(), 404);

        return view('running.announcement', compact('announcement'));
    }
}
