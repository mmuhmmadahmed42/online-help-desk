<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return redirect()->route('admin.index')->with('success', 'New user has been added successfully.');
    }

    // Activate a user's account
    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        return back()->with('success', $user->name . '\'s account has been activated.');
    }

    // Deactivate a user's account (in case Admin wants to revoke access)
    public function deactivate(User $user): RedirectResponse
    {
        $user->update(['is_active' => false]);

        // Force-logout the user immediately by removing their active sessions
        DB::table('sessions')->where('user_id', $user->id)->delete();

        return back()->with('success', $user->name . '\'s account has been deactivated.');
    }

    // Show the "Change Password" form for a specific user
    public function editPassword(User $user): View
    {
        return view('admin.change-password', compact('user'));
    }

    // Update a user's password
    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_notice' => true,
        ]);

        // Mark any pending reset requests from this user as resolved
        PasswordResetRequest::where('user_id', $user->id)
            ->where('resolved', false)
            ->update(['resolved' => true]);

        return redirect()->route('admin.index')->with('success', $user->name . '\'s password has been changed successfully.');
    }

    // JSON: count of pending password reset requests (for the bell badge)
    public function newPasswordRequests()
    {
        return response()->json([
            'count' => PasswordResetRequest::where('resolved', false)->count(),
        ]);
    }

    // JSON: list of pending password reset requests (for the bell dropdown)
    public function passwordRequests()
    {
        $requests = PasswordResetRequest::with('user')
            ->where('resolved', false)
            ->latest()
            ->get()
            ->map(fn ($r) => [
                'user_id' => $r->user_id,
                'name' => $r->user->name,
                'email' => $r->user->email,
                'time' => $r->created_at->diffForHumans(),
            ]);

        return response()->json(['requests' => $requests]);
    }
}