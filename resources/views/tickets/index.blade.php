<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            My Tickets
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-brand-50 text-brand-700 rounded-lg border border-brand-100 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 bg-danger-50 text-danger-600 rounded-lg border border-danger-50 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-slate-500">Track and manage the tickets you've submitted.</p>
                <a href="{{ route('tickets.create') }}" class="inline-flex items-center gap-1.5 bg-brand-500 text-white px-4 py-2 rounded-lg hover:bg-brand-600 transition text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    New Ticket
                </a>
            </div>

            <div class="bg-white overflow-hidden rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $ticket->reference_number }}</td>
                                <td class="px-6 py-4 text-sm text-slate-800 font-medium">{{ $ticket->title }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($ticket->status === 'open') bg-slate-100 text-slate-600
                                        @elseif($ticket->status === 'in_progress') bg-accent-400/15 text-accent-600
                                        @elseif($ticket->status === 'completed') bg-brand-50 text-brand-600
                                        @else bg-slate-100 text-slate-600
                                        @endif">
                                        @if($ticket->status === 'open')
                                            Submitted
                                        @elseif($ticket->status === 'in_progress')
                                            In Progress
                                        @elseif($ticket->status === 'completed')
                                            Completed
                                        @else
                                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-3">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-brand-600 hover:text-brand-700 font-medium">Review</a>

                                    @if ($ticket->isEditableByUser())
                                        <a href="{{ route('tickets.edit', $ticket) }}" class="text-accent-600 hover:text-accent-700 font-medium">Update</a>

                                        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Delete this ticket?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger-500 hover:text-danger-600 font-medium">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <p class="text-sm text-slate-500">You haven't submitted any tickets yet.</p>
                                    <a href="{{ route('tickets.create') }}" class="inline-block mt-2 text-sm text-brand-600 hover:text-brand-700 font-medium">Create your first ticket</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>