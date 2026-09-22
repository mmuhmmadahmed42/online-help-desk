<?php

namespace App\Http\Controllers;

use App\Events\NewTicketNotification;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    // Show all tickets created by the logged-in user
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    // Show the create ticket form
    public function create()
    {
        return view('tickets.create');
    }

    // Store a new ticket
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_path' => $file->store('ticket-attachments', 'public'),
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        try {
            event(new NewTicketNotification($ticket));
        } catch (\Throwable $e) {
            // Reverb server may not be running — don't let that break ticket creation
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    // Review a single ticket
    public function show(Ticket $ticket)
    {
        $this->authorizeTicketOwner($ticket);
        return view('tickets.show', compact('ticket'));
    }

    // Show the edit form (only allowed while ticket is still "open", before PM review)
    public function edit(Ticket $ticket)
    {
        $this->authorizeTicketOwner($ticket);

        if (!$ticket->isEditableByUser()) {
            return redirect()->route('tickets.index')
                ->with('error', 'This ticket can no longer be edited as it has been reviewed by the Project Manager.');
        }

        return view('tickets.edit', compact('ticket'));
    }

    // Update the ticket (only allowed while still "open")
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorizeTicketOwner($ticket);

        if (!$ticket->isEditableByUser()) {
            return redirect()->route('tickets.index')
                ->with('error', 'This ticket can no longer be edited as it has been reviewed by the Project Manager.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $ticket->update($request->only('title', 'description'));

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_path' => $file->store('ticket-attachments', 'public'),
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    // Remove a single attachment from a ticket (only while still editable)
    public function destroyAttachment(TicketAttachment $attachment)
    {
        $ticket = $attachment->ticket;
        $this->authorizeTicketOwner($ticket);

        if (!$ticket->isEditableByUser()) {
            return redirect()->route('tickets.index')
                ->with('error', 'This ticket can no longer be edited as it has been reviewed by the Project Manager.');
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'Attachment removed.');
    }

    // Delete the ticket (only allowed before PM has reviewed/assigned it)
    public function destroy(Ticket $ticket)
    {
        $this->authorizeTicketOwner($ticket);

        if (!$ticket->isEditableByUser()) {
            return redirect()->route('tickets.index')
                ->with('error', 'This ticket can no longer be deleted as it has been reviewed by the Project Manager.');
        }

        if ($ticket->attachment_path) {
            Storage::disk('public')->delete($ticket->attachment_path);
        }

        foreach ($ticket->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    // Make sure the logged-in user owns this ticket
    private function authorizeTicketOwner(Ticket $ticket): void
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
    }
}