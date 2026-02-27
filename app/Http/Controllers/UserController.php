<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * List all users
     */
    public function index()
    {
        $user = auth()->user();
        $query = User::with(['employee', 'site'])->orderBy('username');

        if (!$user->isAdmin()) {
            $query->where('site_id', $user->site_id);
        }

        $users = $query->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = auth()->user();
        $employees = $user->scopedEmployeeQuery()->whereNull('user_id')->orderBy('full_name')->get();
        $sites = \App\Models\Site::orderBy('name')->get();

        return view('users.create', compact('employees', 'sites'));
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'    => 'required|string|max:50|unique:users,username|alpha_dash',
            'name'        => 'required|string|max:100',
            'password'    => ['required', 'confirmed', Password::min(6)],
            'role'        => 'required|in:admin,hr,viewer',
            'site_id'     => 'nullable|exists:sites,id',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'name'     => $validated['name'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'site_id'  => auth()->user()->isAdmin() ? $validated['site_id'] : auth()->user()->site_id,
        ]);

        // Link to employee if selected
        if ($validated['employee_id']) {
            Employee::where('id', $validated['employee_id'])->update(['user_id' => $user->id]);
        }

        return redirect()->route('users.index')->with('success', "Akun \"{$user->username}\" berhasil dibuat.");
    }

    /**
     * Edit user
     */
    public function edit(User $user)
    {
        $currentUser = auth()->user();

        // Security check: HR cannot edit users from other sites
        if (!$currentUser->isAdmin() && $user->site_id !== $currentUser->site_id) {
            abort(403);
        }

        $employees = $currentUser->scopedEmployeeQuery()->where(function ($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->orderBy('full_name')->get();

        $sites = \App\Models\Site::orderBy('name')->get();
        $linkedEmployeeId = Employee::where('user_id', $user->id)->value('id');

        return view('users.edit', compact('user', 'employees', 'sites', 'linkedEmployeeId'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username'    => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'name'        => 'required|string|max:100',
            'password'    => ['nullable', 'confirmed', Password::min(6)],
            'role'        => 'required|in:admin,hr,viewer',
            'site_id'     => 'nullable|exists:sites,id',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $user->update([
            'username' => $validated['username'],
            'name'     => $validated['name'],
            'role'     => $validated['role'],
            'site_id'  => auth()->user()->isAdmin() ? $validated['site_id'] : $user->site_id,
        ]);

        if ($validated['password']) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Unlink previous employee
        Employee::where('user_id', $user->id)->update(['user_id' => null]);

        // Link new employee if selected
        if ($validated['employee_id']) {
            Employee::where('id', $validated['employee_id'])->update(['user_id' => $user->id]);
        }

        return redirect()->route('users.index')->with('success', "Akun \"{$user->username}\" berhasil diperbarui.");
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        // Unlink employee
        Employee::where('user_id', $user->id)->update(['user_id' => null]);

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', "Password \"{$user->username}\" berhasil direset.");
    }
}
