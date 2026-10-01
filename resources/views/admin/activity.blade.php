<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Recent Activity
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Ticket #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Ticket Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Performed By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($activities as $activity)
                            <tr class="hover:bg-slate-50/60 transition">
                                                                <td class="px-6 py-4 text-sm font-mono">
                                    @if ($activity->ticket)
                                        <a href="{{ route('admin.tickets.show', $activity->ticket) }}" class="text-accent-500 hover:text-accent-600 font-medium">
                                            {{ $activity->ticket->reference_number }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $activity->ticket->title ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-800 font-medium">
                                    {{ $activity->user->name ?? 'System' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $activity->user ? str_replace('_', ' ', ucfirst($activity->user->role)) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $activity->description }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-400">
                                    {{ $activity->created_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No activity yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $activities->links() }}
            </div>

        </div>
    </div>
</x-app-layout>