<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Ticket <span class="font-mono text-brand-600">{{ $ticket->reference_number }}</span>
        </h2>
    </x-slot>

    @php
        $currentTeam = auth()->user()->isBackendTeam() ? 'backend' : 'frontend';
    @endphp

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl border border-slate-200 space-y-5">

                @if (session('success'))
                    <div class="px-4 py-3 bg-brand-50 text-brand-700 rounded-lg border border-brand-100 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide">Status</span>
                        <p class="mt-1">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                @if($ticket->status === 'in_progress') bg-accent-400/15 text-accent-600
                                @else bg-brand-50 text-brand-600
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wide">Assigned Team</span>
                        <p class="font-medium text-slate-700 mt-1">{{ $ticket->assigned_team ? ucfirst($ticket->assigned_team) : '—' }}</p>
                    </div>
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

                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wide">Reported by</span>
                    <p class="font-medium text-slate-700 mt-1">{{ $ticket->user->name }}</p>
                </div>

                <hr class="border-slate-100">

                {{-- Work History --}}
                <div>
                    <h3 class="font-semibold text-slate-800 mb-3">Work History</h3>

                    @forelse ($histories as $history)
                        <div class="border-l-2 {{ $history->team === 'backend' ? 'border-accent-400' : 'border-brand-400' }} bg-slate-50 p-3 rounded-r-lg mb-2">
                            <p class="text-sm font-semibold text-slate-800">{{ $history->action }}</p>
                            <p class="text-sm text-slate-600 mt-1">{{ $history->details }}</p>
                            <p class="text-xs text-slate-400 mt-1.5">
                                {{ $history->user->name }} ({{ ucfirst($history->team) }} team) &middot; {{ $history->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No work history yet.</p>
                    @endforelse
                </div>

                {{-- Work details form --}}
                @if ($ticket->isInProgress() && $ticket->assigned_team === $currentTeam)
                    <form action="{{ route('team.complete', $ticket) }}" method="POST"
                          class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                        @csrf

                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Work Details (kia kam kia?) <span class="text-danger-500">*</span>
                        </label>
                        <textarea name="work_details" rows="3" required
                            placeholder="Describe the work you did on this ticket..."
                            class="w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">{{ old('work_details') }}</textarea>

                        @error('work_details')
                            <p class="text-sm text-danger-500 mt-1">{{ $message }}</p>
                        @enderror

                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            @if ($currentTeam === 'backend')
                                <button type="submit" name="action" value="send_to_frontend"
                                    onclick="return confirm('Send this ticket to the Frontend team?');"
                                    class="bg-brand-500 text-white px-4 py-2 rounded-lg hover:bg-brand-600 transition text-sm font-medium">
                                    Complete &amp; Send to Frontend
                                </button>
                            @endif

                            <button type="submit" name="action" value="complete"
                                onclick="return confirm('{{ $currentTeam === 'backend' ? 'Close this ticket as completed? It will NOT go to the Frontend team.' : 'Mark this ticket as completed?' }}');"
                                class="px-4 py-2 rounded-lg transition text-sm font-medium {{ $currentTeam === 'backend' ? 'border border-brand-200 bg-white text-brand-700 hover:bg-brand-50' : 'bg-brand-500 text-white hover:bg-brand-600' }}">
                                Mark as Completed
                            </button>
                        </div>

                        @if ($currentTeam === 'backend')
                            <p class="text-xs text-slate-500 mt-2">
                                Frontend ka kam bhi baaqi hai to "Complete &amp; Send to Frontend" dabayein. Ticket yahin band karna hai to "Mark as Completed" dabayein.
                            </p>
                        @endif
                    </form>
                @endif

                <div class="pt-1">
                    <a href="{{ route('team.index') }}" class="text-brand-600 hover:text-brand-700 text-sm font-medium">&larr; Back to My Tickets</a>
                </div>

                <hr class="border-slate-100">

                {{-- Comments --}}
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
                        <p class="text-sm text-slate-500 mb-3">No comments yet.</p>
                    @endforelse

                    <form action="{{ route('team.comment', $ticket) }}" method="POST" class="mt-4">
                        @csrf
                        <textarea name="comment" rows="3" placeholder="Write a comment (e.g. ticket incomplete, wrong details, etc.)"
                            class="w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400"></textarea>
                        <button type="submit" class="mt-2 bg-brand-500 text-white px-4 py-2 rounded-lg hover:bg-brand-600 transition text-sm font-medium">
                            Add Comment
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>