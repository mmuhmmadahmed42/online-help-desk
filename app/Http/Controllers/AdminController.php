<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\PasswordResetRequest;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('is_active', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.index', compact('users'));
    }

    public function activity(): View
    {
        $activities = ActivityLog::with(['user', 'ticket'])->latest()->paginate(20);

        return view('admin.activity', compact('activities'));
    }

    // Admin view of a single ticket's full detail
    public function showTicket(Ticket $ticket): View
    {
        $ticket->load(['user', 'assignedBy', 'comments.user', 'attachments']);

        return view('admin.ticket-show', compact('ticket'));
    }

    public function create(): View
    {
        return view('admin.create');
    }

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

    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        return back()->with('success', $user->name . '\'s account has been activated.');
    }

    public function deactivate(User $user): RedirectResponse
    {
        $user->update(['is_active' => false]);

        DB::table('sessions')->where('user_id', $user->id)->delete();

        return back()->with('success', $user->name . '\'s account has been deactivated.');
    }

    public function editPassword(User $user): View
    {
        return view('admin.change-password', compact('user'));
    }

    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_notice' => true,
        ]);

        PasswordResetRequest::where('user_id', $user->id)
            ->where('resolved', false)
            ->update(['resolved' => true]);

        return redirect()->route('admin.index')->with('success', $user->name . '\'s password has been changed to: ' . $request->password);
    }

    public function newPasswordRequests()
    {
        return response()->json([
            'count' => PasswordResetRequest::where('resolved', false)->count(),
        ]);
    }

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

    public function newActivityCount()
    {
        return response()->json([
            'count' => ActivityLog::where('seen', false)->count(),
        ]);
    }

    public function activityFeed()
    {
        $activities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($a) => [
                'description' => $a->description,
                'time' => $a->created_at->diffForHumans(),
            ]);

        ActivityLog::where('seen', false)->update(['seen' => true]);

        return response()->json(['activities' => $activities]);
    }
}