<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Ticket <span class="font-mono text-brand-600">{{ $ticket->reference_number }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl border border-slate-200 space-y-5">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide">Status</span>
                        <p class="mt-1">
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
                        </p>
                    </div>
                    @if ($ticket->assigned_team)
                        <div>
                            <span class="text-xs text-slate-400 uppercase tracking-wide">Assigned Team</span>
                            <p class="font-medium text-slate-700 mt-1">{{ ucfirst($ticket->assigned_team) }}</p>
                        </div>
                    @endif
                </div>

                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wide">Reference Number</span>
                    <p class="font-mono font-medium text-slate-700 mt-1">{{ $ticket->reference_number }}</p>
                </div>

                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wide">Title</span>
                    <p class="font-medium text-slate-800 mt-1">{{ $ticket->title }}</p>
                </div>

                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wide">Description</span>
                    <p class="text-slate-600 mt-1">{{ $ticket->description }}</p>
                </div>

                @if ($ticket->hasAttachment())
                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide">Attachments</span>
                        <div class="mt-1 space-y-1.5">
                            @if ($ticket->attachment_path)
                                <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank"
                                    class="flex items-center gap-2 text-sm text-brand-600 hover:text-brand-700 font-medium w-fit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    {{ $ticket->attachment_name }}
                                </a>
                            @endif

                            @foreach ($ticket->attachments as $attachment)
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                    class="flex items-center gap-2 text-sm text-brand-600 hover:text-brand-700 font-medium w-fit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    {{ $attachment->file_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="pt-1">
                    <a href="{{ route('tickets.index') }}" class="text-brand-600 hover:text-brand-700 text-sm font-medium">&larr; Back to My Tickets</a>
                </div>

                <hr class="border-slate-100">

                <div>
                    <h3 class="font-semibold text-slate-800 mb-3">Comments</h3>

                    @forelse ($ticket->comments as $comment)
                        <div class="bg-slate-50 p-3 rounded-lg mb-2 border border-slate-100">
                            <p class="text-sm text-slate-700">{{ $comment->comment }}</p>
                            <p class="text-xs text-slate-400 mt-1">
                                {{ $comment->user->name }} &middot; {{ $comment->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No comments yet.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>