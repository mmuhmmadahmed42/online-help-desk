<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminController extends Controller
{
    // Show all users, pending (inactive) ones first
    public function index(): View
    {
        $users = User::orderBy('is_active', 'asc')->orderBy('created_at', 'desc')->get();

        return view('admin.index', compact('users'));
    }

    // Show the "Add New User" form
    public function create(): View
    {
        return view('admin.create');
    }

    // Store a new user created by Admin (auto-activated)
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,project_manager,backend_team,frontend_team,admin'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('admin.index')->with('success', 'Naya user successfully add kar diya gaya.');
    }

    // Activate a user's account
    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        return back()->with('success', $user->name . ' ka account activate kar diya gaya.');
    }

    // Deactivate a user's account (in case Admin wants to revoke access)
    public function deactivate(User $user): RedirectResponse
    {
        $user->update(['is_active' => false]);

        return back()->with('success', $user->name . ' ka account deactivate kar diya gaya.');
    }
}