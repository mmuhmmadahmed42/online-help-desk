<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    // Show tickets assigned to the logged-in team member's team
    public function index()
    {
        $team = Auth::user()->isBackendTeam() ? 'backend' : 'frontend';

        $tickets = Ticket::with('user')
            ->where('assigned_team', $team)
            ->whereIn('status', ['in_progress', 'completed'])
            ->latest()
            ->get();

        return view('team.index', compact('tickets'));
    }

    // Tickets on which the logged-in team member has done work
    public function myHistory()
    {
        $entries = TicketHistory::with('ticket.user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('team.my-history', compact('entries'));
    }

    // Show single ticket with work history and comments
    public function show(Ticket $ticket)
    {
        $histories = TicketHistory::with('user')
            ->where('ticket_id', $ticket->id)
            ->latest()
            ->get();

        return view('team.show', compact('ticket', 'histories'));
    }

    // Full work history page for a ticket (oldest first)
    public function history(Ticket $ticket)
    {
        $histories = TicketHistory::with('user')
            ->where('ticket_id', $ticket->id)
            ->oldest()
            ->get();

        return view('team.history', compact('ticket', 'histories'));
    }

    // Add a comment to a ticket
    public function comment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        ActivityLog::log(
            'ticket_comment',
            Auth::user()->name . ' added a comment on ticket "' . $ticket->title . '" (' . $ticket->reference_number . ')',
            $ticket->id,
            Auth::id()
        );

        return redirect()->route('team.show', $ticket)->with('success', 'Comment added.');
    }

    // Two actions:
    //  - send_to_frontend (sirf Backend): history save + ticket Frontend team ko
    //  - complete: history save + ticket final completed (dono teams)
    public function complete(Request $request, Ticket $ticket)
    {
        $request->validate([
            'work_details' => 'required|string|max:2000',
            'action' => 'required|in:complete,send_to_frontend',
        ]);

        $user = Auth::user();
        $currentTeam = $user->isBackendTeam() ? 'backend' : 'frontend';

        abort_if($ticket->assigned_team !== $currentTeam, 403);
        abort_if($ticket->status !== 'in_progress', 403);
        abort_if($request->action === 'send_to_frontend' && $currentTeam !== 'backend', 403);

        if ($request->action === 'send_to_frontend') {
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'team' => 'backend',
                'action' => 'Backend work completed — passed to Frontend team',
                'details' => $request->work_details,
            ]);

            $ticket->assigned_team = 'frontend';
            $ticket->save();

            ActivityLog::log(
                'team_work',
                $user->name . ' (Backend) completed work on "' . $ticket->title . '" (' . $ticket->reference_number . ') and sent it to Frontend team',
                $ticket->id,
                $user->id
            );

            return redirect()->route('team.index')->with('success', 'Backend work done. Ticket sent to Frontend team.');
        }

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'team' => $currentTeam,
            'action' => ucfirst($currentTeam) . ' work completed — ticket closed',
            'details' => $request->work_details,
        ]);

        $ticket->update(['status' => 'completed']);

        ActivityLog::log(
            'ticket_completed',
            $user->name . ' (' . ucfirst($currentTeam) . ') marked ticket "' . $ticket->title . '" (' . $ticket->reference_number . ') as completed',
            $ticket->id,
            $user->id
        );

        return redirect()->route('team.show', $ticket)->with('success', 'Ticket marked as completed.');
    }
}