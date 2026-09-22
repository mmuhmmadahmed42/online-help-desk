<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            My Assigned Tickets
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-brand-50 text-brand-700 rounded-lg border border-brand-100 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <p class="text-sm text-slate-500 mb-6">Tickets that have been assigned to your team.</p>

            <div class="bg-white overflow-hidden rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Created By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $ticket->reference_number }}</td>
                                <td class="px-6 py-4 text-sm text-slate-800 font-medium">{{ $ticket->title }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $ticket->user->name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($ticket->status === 'in_progress') bg-accent-400/15 text-accent-600
                                        @else bg-brand-50 text-brand-600
                                        @endif">
                                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('team.show', $ticket) }}" class="text-brand-600 hover:text-brand-700 font-medium">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">No tickets assigned yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>