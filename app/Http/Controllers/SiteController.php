<?php

namespace App\Http\Controllers;

use App\Models\EmployeeContract;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Site::query();

        if (!$user->isAdmin()) {
            $query->where('id', $user->site_id);
        }

        $sites = $query->withCount('employees')
            ->orderBy('name')
            ->paginate(15);

        return view('sites.index', compact('sites'));
    }

    /**
     * Show the form for creating a new site.
     */
    public function create()
    {
        return view('sites.create');
    }

    /**
     * Store a newly created site.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'code'     => ['required', 'string', 'max:20', 'unique:sites,code'],
            'address'  => ['nullable', 'string'],
            'city'     => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
        ]);

        Site::create($validated);

        return redirect()
            ->route('sites.index')
            ->with('success', 'Site berhasil ditambahkan.');
    }

    /**
     * Display the specified site with its employees and contract overview.
     */
    public function show(Site $site)
    {
        $site->loadCount('employees');

        $employees = $site->employees()
            ->with(['department', 'contracts' => fn($q) => $q->orderByDesc('end_date')])
            ->orderBy('full_name')
            ->paginate(15);

        // Contracts expiring within 30 days for this site
        $expiringContracts = EmployeeContract::whereHas('employee', fn($q) => $q->where('site_id', $site->id))
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(30))
            ->with('employee')
            ->orderBy('end_date')
            ->get();

        return view('sites.show', compact('site', 'employees', 'expiringContracts'));
    }

    /**
     * Show the form for editing the specified site.
     */
    public function edit(Site $site)
    {
        return view('sites.edit', compact('site'));
    }

    /**
     * Update the specified site.
     */
    public function update(Request $request, Site $site)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'code'     => ['required', 'string', 'max:20', 'unique:sites,code,' . $site->id],
            'address'  => ['nullable', 'string'],
            'city'     => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
        ]);

        $site->update($validated);

        return redirect()
            ->route('sites.index')
            ->with('success', 'Site berhasil diperbarui.');
    }

    /**
     * Remove the specified site.
     */
    public function destroy(Site $site)
    {
        if ($site->employees()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus site yang masih memiliki karyawan.');
        }

        $site->delete();

        return redirect()
            ->route('sites.index')
            ->with('success', 'Site berhasil dihapus.');
    }
}
