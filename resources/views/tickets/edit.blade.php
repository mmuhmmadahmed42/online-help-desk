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

            {{-- Existing attachments — kept OUTSIDE the update form to avoid nested <form> tags --}}
            @if ($ticket->attachments->count() || $ticket->attachment_path)
                <div class="bg-white p-6 rounded-xl border border-slate-200 mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Existing Attachments</label>

                    <div class="space-y-2">
                        @if ($ticket->attachment_path)
                            <div class="flex items-center justify-between gap-2 text-sm bg-slate-50 border border-slate-100 rounded-lg px-3 py-2">
                                <a href="{{ Storage::url($ticket->attachment_path) }}" target="_blank" class="flex items-center gap-2 text-brand-600 hover:text-brand-700 font-medium truncate">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span class="truncate">{{ $ticket->attachment_name }}</span>
                                </a>
                            </div>
                        @endif

                        @foreach ($ticket->attachments as $attachment)
                            <div class="flex items-center justify-between gap-2 text-sm bg-slate-50 border border-slate-100 rounded-lg px-3 py-2">
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="flex items-center gap-2 text-brand-600 hover:text-brand-700 font-medium truncate">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span class="truncate">{{ $attachment->file_name }}</span>
                                </a>
                                <form action="{{ route('attachments.destroy', $attachment) }}" method="POST" class="remove-attachment-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-danger-500 hover:text-danger-600 text-xs font-medium flex-shrink-0 remove-attachment-btn">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
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
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Add More Attachments (optional)</label>

                        <label for="attachments" class="flex items-center gap-2 w-full border border-dashed border-slate-300 rounded-lg px-4 py-3 cursor-pointer hover:border-brand-400 hover:bg-slate-50 transition">
                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="text-sm text-slate-500" id="attachments-label">Add more files (max 5MB each)</span>
                        </label>
                        <input id="attachments" type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="hidden">
                        @error('attachments.*')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror

                        {{-- Selected files preview list --}}
                        <div id="selectedFilesWrapper" class="mt-3 hidden">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Selected Files</span>
                                <button type="button" id="removeAllFilesBtn" class="text-xs text-danger-500 hover:text-danger-600 font-medium">
                                    Remove All
                                </button>
                            </div>
                            <div id="selectedFilesList" class="space-y-1.5"></div>
                        </div>
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

    {{-- Custom confirmation modal (replaces browser's default confirm popup) --}}
    <div id="removeConfirmModal" class="fixed inset-0 bg-slate-900/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Remove this file?</h3>
            <p class="text-sm text-slate-500 mb-5">This action cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelRemoveBtn" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition">
                    Cancel
                </button>
                <button type="button" id="confirmRemoveBtn" class="px-4 py-2 rounded-lg text-sm font-medium bg-danger-500 text-white hover:bg-danger-600 transition">
                    Remove
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ---- Existing attachment remove modal ----
            const modal = document.getElementById('removeConfirmModal');
            const cancelBtn = document.getElementById('cancelRemoveBtn');
            const confirmBtn = document.getElementById('confirmRemoveBtn');
            let formToSubmit = null;

            document.querySelectorAll('.remove-attachment-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    formToSubmit = btn.closest('form');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            cancelBtn.addEventListener('click', function () {
                formToSubmit = null;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            confirmBtn.addEventListener('click', function () {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
            });

            // ---- New files preview list (Remove / Remove All) ----
            const input = document.getElementById('attachments');
            const label = document.getElementById('attachments-label');
            const wrapper = document.getElementById('selectedFilesWrapper');
            const list = document.getElementById('selectedFilesList');
            const removeAllBtn = document.getElementById('removeAllFilesBtn');

            let fileStore = [];

            function refreshInputFiles() {
                const dataTransfer = new DataTransfer();
                fileStore.forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files;
            }

            function renderList() {
                list.innerHTML = '';

                if (fileStore.length === 0) {
                    wrapper.classList.add('hidden');
                    label.textContent = 'Add more files (max 5MB each)';
                    return;
                }

                wrapper.classList.remove('hidden');
                label.textContent = fileStore.length + ' file(s) selected';

                fileStore.forEach(function (file, index) {
                    const row = document.createElement('div');
                    row.className = 'flex items-center justify-between gap-2 text-sm bg-slate-50 border border-slate-100 rounded-lg px-3 py-2';

                    const nameSpan = document.createElement('span');
                    nameSpan.className = 'truncate text-slate-600';
                    nameSpan.textContent = file.name;

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'text-danger-500 hover:text-danger-600 text-xs font-medium flex-shrink-0';
                    removeBtn.textContent = 'Remove';
                    removeBtn.addEventListener('click', function () {
                        fileStore.splice(index, 1);
                        refreshInputFiles();
                        renderList();
                    });

                    row.appendChild(nameSpan);
                    row.appendChild(removeBtn);
                    list.appendChild(row);
                });
            }

            input.addEventListener('change', function () {
                Array.from(input.files).forEach(function (file) {
                    const alreadyExists = fileStore.some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified);
                    if (!alreadyExists) {
                        fileStore.push(file);
                    }
                });
                refreshInputFiles();
                renderList();
            });

            removeAllBtn.addEventListener('click', function () {
                fileStore = [];
                refreshInputFiles();
                renderList();
            });
        });
    </script>
</x-app-layout>