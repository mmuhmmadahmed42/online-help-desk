<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            All Tickets — Project Manager
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-brand-50 text-brand-700 rounded-lg border border-brand-100 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <p class="text-sm text-slate-500 mb-6">Review incoming tickets and assign them to the right team.</p>

            <div class="bg-white overflow-hidden rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Created By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Team</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $ticket->reference_number }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($ticket->attachment_path)
                                        <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="text-slate-400 hover:text-brand-600 inline-block" title="{{ $ticket->attachment_name }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                        </a>
                                    @endif
                                    @foreach ($ticket->attachments as $attachment)
                                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-slate-400 hover:text-brand-600 inline-block ml-1" title="{{ $attachment->file_name }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                        </a>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-800 font-medium">{{ $ticket->title }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $ticket->user->name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($ticket->status === 'open') bg-slate-100 text-slate-600
                                        @elseif($ticket->status === 'in_progress') bg-accent-400/15 text-accent-600
                                        @elseif($ticket->status === 'completed') bg-brand-50 text-brand-600
                                        @else bg-slate-100 text-slate-600
                                        @endif">
                                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $ticket->assigned_team ? ucfirst($ticket->assigned_team) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($ticket->isOpen())
                                        <form action="{{ route('pm.assign', $ticket) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <select name="assigned_team" class="text-sm border-slate-200 rounded-lg focus:border-brand-400 focus:ring-brand-400" required>
                                                <option value="">Assign to...</option>
                                                <option value="backend">Backend Team</option>
                                                <option value="frontend">Frontend Team</option>
                                            </select>
                                            <button type="submit" class="bg-brand-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-brand-600 transition font-medium">
                                                Assign
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-300 text-sm">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-sm text-slate-500">No tickets yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function initEcho() {
                if (typeof window.Echo === 'undefined') {
                    setTimeout(initEcho, 200);
                    return;
                }

                window.Echo.private('pm-notifications')
                    .listen('.new-ticket-notification', (e) => {
                        showTicketPopup(e);
                        // Bump the bell badge live too
                        window.dispatchEvent(new CustomEvent('pm-new-ticket'));
                    });
            }

            function showTicketPopup(e) {
                const popup = document.createElement('div');
                popup.className = 'fixed top-4 right-4 z-50 bg-white border border-brand-100 shadow-lg rounded-xl px-4 py-3 max-w-sm';
                popup.innerHTML = `
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-brand-50 flex items-center justify-center text-brand-600">🔔</span>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-800">New ticket received</p>
                            <p class="text-sm text-slate-500">${e.reference_number} — ${e.title}</p>
                            <button onclick="window.location.reload()" class="text-xs text-brand-600 hover:text-brand-700 font-medium mt-1.5">Refresh to view</button>
                        </div>
                        <button class="text-slate-300 hover:text-slate-500" onclick="this.closest('div.fixed').remove()">✕</button>
                    </div>
                `;
                document.body.appendChild(popup);

                setTimeout(() => popup.remove(), 8000);
            }

            initEcho();
        });
    </script>
</x-app-layout>