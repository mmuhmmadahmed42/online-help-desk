<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectManagerController extends Controller
{
    // Show all tickets (so PM can see everything and assign what's still open)
    public function index()
    {
        $tickets = Ticket::with('user')->latest()->get();
        return view('pm.index', compact('tickets'));
    }

    // Assign a ticket to Backend or Frontend team
    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_team' => 'required|in:backend,frontend',
        ]);

        $ticket->assignToTeam($request->assigned_team, $request->user());

        ActivityLog::log(
            'ticket_assigned',
            $request->user()->name . ' assigned ticket "' . $ticket->title . '" (' . $ticket->reference_number . ') to ' . ucfirst($request->assigned_team) . ' team',
            $ticket->id,
            $request->user()->id
        );

        return redirect()->route('pm.index')->with('success', 'Ticket assigned to ' . ucfirst($request->assigned_team) . ' team.');
    }

    // Returns just the unread count + list, for the bell badge (does NOT mark as read)
    public function newTickets()
    {
        $lastSeen = session('pm_notifications_last_seen');

        $tickets = $lastSeen
            ? Ticket::with('user')->where('created_at', '>', $lastSeen)->latest()->get()
            : Ticket::with('user')->latest()->limit(10)->get();

        return response()->json([
            'count' => $lastSeen ? $tickets->count() : 0,
        ]);
    }

    // Returns the dropdown list AND marks everything as read
    public function notifications()
    {
        $lastSeen = session('pm_notifications_last_seen');

        $tickets = $lastSeen
            ? Ticket::with('user')->where('created_at', '>', $lastSeen)->latest()->get()
            : Ticket::with('user')->latest()->limit(5)->get();

        $data = $tickets->map(fn ($t) => [
            'reference_number' => $t->reference_number,
            'title' => $t->title,
            'created_by' => $t->user->name,
            'time' => $t->created_at->diffForHumans(),
        ]);

        session(['pm_notifications_last_seen' => now()]);

        return response()->json(['tickets' => $data]);
    }
}