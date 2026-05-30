<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $announcements = Announcement::published()->orderByDesc('published_at')->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function show(string $slug)
    {
        $announcement = Announcement::where('slug',$slug)->firstOrFail();
        if ($announcement->status !== 'published') abort(404);
        return view('announcements.show', compact('announcement'));
    }
}
