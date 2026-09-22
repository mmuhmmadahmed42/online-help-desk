<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Update Ticket <span class="font-mono text-brand-600">{{ $ticket->reference_number }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <p class="text-sm text-slate-500 mb-6">You can edit this ticket until it's picked up by the Project Manager.</p>

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-brand-50 text-brand-700 rounded-lg border border-brand-100 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <form action="{{ route('tickets.update', $ticket) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Title</label>
                        <input type="text" name="title" value="{{ old('title', $ticket->title) }}"
                            class="block w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">
                        @error('title')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                        <textarea name="description" rows="5"
                            class="block w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Attachments (optional)</label>

                        @if ($ticket->attachments->count())
                            <div class="space-y-2 mb-3">
                                @foreach ($ticket->attachments as $attachment)
                                    <div class="flex items-center justify-between gap-2 text-sm bg-slate-50 border border-slate-100 rounded-lg px-3 py-2">
                                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="flex items-center gap-2 text-brand-600 hover:text-brand-700 font-medium truncate">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                            <span class="truncate">{{ $attachment->file_name }}</span>
                                        </a>
                                        <form action="{{ route('attachments.destroy', $attachment) }}" method="POST" onsubmit="return confirm('Remove this file?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger-500 hover:text-danger-600 text-xs font-medium flex-shrink-0">Remove</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Old single-attachment field, kept for tickets created before multi-attachment support --}}
                        @if ($ticket->attachment_path)
                            <div class="flex items-center gap-2 text-sm bg-slate-50 border border-slate-100 rounded-lg px-3 py-2 mb-3">
                                <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="flex items-center gap-2 text-brand-600 hover:text-brand-700 font-medium truncate">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span class="truncate">{{ $ticket->attachment_name }}</span>
                                </a>
                            </div>
                        @endif

                        <label for="attachments" class="flex items-center gap-2 w-full border border-dashed border-slate-300 rounded-lg px-4 py-3 cursor-pointer hover:border-brand-400 hover:bg-slate-50 transition">
                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="text-sm text-slate-500" id="attachments-label">Add more files (max 5MB each)</span>
                        </label>
                        <input id="attachments" type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="hidden"
                            onchange="document.getElementById('attachments-label').textContent = this.files.length ? this.files.length + ' file(s) selected' : 'Add more files (max 5MB each)'">
                        @error('attachments.*')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-brand-500 text-white px-5 py-2.5 rounded-lg hover:bg-brand-600 transition text-sm font-medium shadow-sm">
                            Update Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>