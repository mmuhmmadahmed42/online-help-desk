<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Work History — <span class="font-mono text-brand-600">{{ $ticket->reference_number }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100 space-y-4">

                <div>
                    <span class="text-sm text-gray-500">Title</span>
                    <p class="font-medium">{{ $ticket->title }}</p>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Reported by</span>
                        <p class="font-medium">{{ $ticket->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Status</span>
                        <p class="font-medium">{{ str_replace('_', ' ', ucfirst($ticket->status)) }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Assigned Team</span>
                        <p class="font-medium">{{ $ticket->assigned_team ? ucfirst($ticket->assigned_team) : '—' }}</p>
                    </div>
                </div>

                <hr class="border-gray-100">

                <h3 class="font-semibold text-gray-800">History</h3>

                @forelse ($histories as $history)
                    <div class="border-l-4 {{ $history->team === 'backend' ? 'border-accent-400' : 'border-brand-400' }} bg-gray-50 p-4 rounded-md">
                        <p class="text-xs text-gray-400">Step {{ $loop->iteration }}</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">{{ $history->action }}</p>
                        <p class="text-sm text-gray-700 mt-2">{{ $history->details }}</p>
                        <p class="text-xs text-gray-500 mt-2">
                            Done by <span class="font-medium">{{ $history->user->name }}</span>
                            ({{ ucfirst($history->team) }} team) &middot; {{ $history->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No work history yet.</p>
                @endforelse

                <div class="pt-4 flex items-center gap-4">
                    <a href="{{ route('team.index') }}" class="text-brand-500 hover:text-brand-600 text-sm font-medium">&larr; Back to My Tickets</a>
                    <a href="{{ route('team.show', $ticket) }}" class="text-brand-500 hover:text-brand-600 text-sm font-medium">Open Ticket</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>