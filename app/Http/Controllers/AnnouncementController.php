<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->latest()
            ->paginate(20);

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'priority'     => 'required|in:low,normal,high,urgent',
            'publish_date' => 'nullable|date',
            'expire_date'  => 'nullable|date|after_or_equal:publish_date',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active']  = true;

        Announcement::create($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required|string',
            'priority'     => 'required|in:low,normal,high,urgent',
            'publish_date' => 'nullable|date',
            'expire_date'  => 'nullable|date|after_or_equal:publish_date',
            'is_active'    => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
