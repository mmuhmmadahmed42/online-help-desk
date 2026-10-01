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
                        <div id="description-editor" style="min-height: 150px;" class="bg-white rounded-lg"></div>
                        <textarea name="description" id="description" class="hidden">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                                                           <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Attachments (optional)</label>
                        <label for="attachments" id="dropZone" class="flex flex-col items-center justify-center gap-2 w-full border-2 border-dashed border-brand-300 bg-brand-50/40 rounded-xl px-6 py-6 cursor-pointer hover:border-brand-500 hover:bg-brand-50 transition text-center">
                            <svg class="w-8 h-8 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <span class="inline-flex items-center gap-2 bg-brand-500 text-white px-4 py-1.5 rounded-lg text-sm font-medium shadow-sm hover:bg-brand-600 transition">
                                Choose Files
                            </span>
                            <span class="text-xs text-slate-500" id="attachments-label">or drop documents/images here (max 5MB each)</span>
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
                            Submit Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('attachments');
            const label = document.getElementById('attachments-label');
            const wrapper = document.getElementById('selectedFilesWrapper');
            const list = document.getElementById('selectedFilesList');
            const removeAllBtn = document.getElementById('removeAllFilesBtn');
            const dropZone = document.getElementById('dropZone');

            let fileStore = []; // holds the actual File objects we keep

            function refreshInputFiles() {
                const dataTransfer = new DataTransfer();
                fileStore.forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files;
            }

            function addFiles(newFiles) {
                Array.from(newFiles).forEach(function (file) {
                    const alreadyExists = fileStore.some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified);
                    if (!alreadyExists) {
                        fileStore.push(file);
                    }
                });
                refreshInputFiles();
                renderList();
            }

            function renderList() {
                list.innerHTML = '';

                if (fileStore.length === 0) {
                    wrapper.classList.add('hidden');
                    label.textContent = 'Attach or drop documents/images (max 5MB each)';
                    return;
                }

                wrapper.classList.remove('hidden');
                label.textContent = fileStore.length + ' file(s) selected';

                fileStore.forEach(function (file, index) {
                    const row = document.createElement('div');
                    row.className = 'flex items-center justify-between gap-2 text-sm bg-slate-50 border border-slate-100 rounded-lg px-3 py-2';

                    const leftSide = document.createElement('div');
                    leftSide.className = 'flex items-center gap-2 min-w-0';

                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.className = 'w-8 h-8 rounded object-cover flex-shrink-0 border border-slate-200';
                        img.onload = function () { URL.revokeObjectURL(img.src); };
                        leftSide.appendChild(img);
                    } else {
                        const iconWrap = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                        iconWrap.setAttribute('class', 'w-4 h-4 flex-shrink-0 text-slate-400');
                        iconWrap.setAttribute('fill', 'none');
                        iconWrap.setAttribute('viewBox', '0 0 24 24');
                        iconWrap.setAttribute('stroke', 'currentColor');
                        iconWrap.setAttribute('stroke-width', '2');
                        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        path.setAttribute('stroke-linecap', 'round');
                        path.setAttribute('stroke-linejoin', 'round');
                        path.setAttribute('d', 'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13');
                        iconWrap.appendChild(path);
                        leftSide.appendChild(iconWrap);
                    }

                    const nameSpan = document.createElement('span');
                    nameSpan.className = 'truncate text-slate-600';
                    nameSpan.textContent = file.name;
                    leftSide.appendChild(nameSpan);

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'text-danger-500 hover:text-danger-600 text-xs font-medium flex-shrink-0';
                    removeBtn.textContent = 'Remove';
                    removeBtn.addEventListener('click', function () {
                        fileStore.splice(index, 1);
                        refreshInputFiles();
                        renderList();
                    });

                    row.appendChild(leftSide);
                    row.appendChild(removeBtn);
                    list.appendChild(row);
                });
            }

            input.addEventListener('change', function () {
                addFiles(input.files);
            });

            removeAllBtn.addEventListener('click', function () {
                fileStore = [];
                refreshInputFiles();
                renderList();
            });

            // ---- Drag and drop support ----
            ['dragenter', 'dragover'].forEach(function (eventName) {
                dropZone.addEventListener(eventName, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('border-brand-400', 'bg-slate-50');
                });
            });

            ['dragleave', 'drop'].forEach(function (eventName) {
                dropZone.addEventListener(eventName, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('border-brand-400', 'bg-slate-50');
                });
            });

            dropZone.addEventListener('drop', function (e) {
                addFiles(e.dataTransfer.files);
            });

            // ---- Quill rich text editor ----
                       const descriptionQuill = new Quill('#description-editor', {
                theme: 'snow',
                placeholder: "What happened? Steps to reproduce, if any.",
                modules: {
                    toolbar: [['bold', 'italic', 'underline'], [{ list: 'ordered' }, { list: 'bullet' }], ['link'], ['clean']]
                }
            });
            descriptionQuill.root.innerHTML = document.getElementById('description').value;

            descriptionQuill.on('text-change', function () {
                document.getElementById('description').value = descriptionQuill.root.innerHTML;
            });
        });
    </script>
</x-app-layout>