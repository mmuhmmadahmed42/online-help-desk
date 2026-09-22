<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ticket History
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reported By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Work Done</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($entries as $entry)
                            <tr class="hover:bg-gray-50/60">
                                <td class="px-6 py-4 text-sm text-gray-700 font-mono">{{ $entry->ticket->reference_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $entry->ticket->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $entry->ticket->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($entry->details, 60) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $entry->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('team.history', $entry->ticket) }}" class="inline-flex items-center px-3 py-1 rounded-md border border-brand-200 bg-brand-50 text-brand-700 text-xs font-medium hover:bg-brand-100 transition">
                                        View History
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-sm text-gray-500 text-center">You have not worked on any ticket yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>