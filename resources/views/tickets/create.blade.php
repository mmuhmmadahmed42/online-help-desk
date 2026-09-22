<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Create New Ticket
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <p class="text-sm text-slate-500 mb-6">Describe the issue you're facing and we'll get it reviewed.</p>

            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            placeholder="A short summary of the issue"
                            class="block w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">
                        @error('title')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                        <textarea name="description" rows="5"
                            placeholder="What happened? Steps to reproduce, if any."
                            class="block w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Attachments (optional)</label>
                        <label for="attachments" class="flex items-center gap-2 w-full border border-dashed border-slate-300 rounded-lg px-4 py-3 cursor-pointer hover:border-brand-400 hover:bg-slate-50 transition">
                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="text-sm text-slate-500" id="attachments-label">Attach documents or images (max 5MB each)</span>
                        </label>
                        <input id="attachments" type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="hidden"
                            onchange="document.getElementById('attachments-label').textContent = this.files.length ? this.files.length + ' file(s) selected' : 'Attach documents or images (max 5MB each)'">
                        @error('attachments.*')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-brand-500 text-white px-5 py-2.5 rounded-lg hover:bg-brand-600 transition text-sm font-medium shadow-sm">
                            Submit Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>