@extends('layouts.app')
@section('company', 'IT Departments')
@section('header', 'Create a New Ticket')
@section('subtitle', 'Report your problem or request')
@section('content')
    <div class="px-4 space-y-6 pb-8">
        <div class="bg-gradient-to-r from-blue-500/10 to-cyan-500/10 border border-blue-500/30 rounded-2xl p-4">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-blue-400 mb-1">Tips for Making Tickets</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Please describe your issue in detail for a faster
                        response. Our team will respond within 1-2 business hours.</p>
                </div>
            </div>
        </div>
        <form id="ticketForm"
      method="POST"
      action="{{ route('ticketreq') }}"
      enctype="multipart/form-data"
      class="space-y-5">
        {{-- <form  method="POST" action="{{ route('ticketreq') }}" enctype="multipart/form-data" class="space-y-5"> --}}
            @csrf
            <input type="hidden" name="request_uuid" value="{{ Str::uuid() }}">

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <span>Ticket Title</span>
                    <span class="text-red-400">*</span>
                </label>
                <input type="text" id="title" name="title" required
                    placeholder="Example: Laptop cannot connect to WiFi"
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    value="{{ old('title') }}">
                @error('title')
                    <p class="mt-2 text-sm text-red-400 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>
            <div>
                <label for="category" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>Categories</span>
                    <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <select id="category" name="category" required
                        class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none cursor-pointer">
                        <option value="" class="bg-slate-800">Choose Categories...</option>
                        <option value="Hardware & Software" class="bg-slate-800">Hardware & Software</option>
                        <option value="Network" class="bg-slate-800">Network</option>
                        <option value="Account & Access" class="bg-slate-800">Account & Access</option>
                        <option value="Others" class="bg-slate-800">Others</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('category')
                    <p class="mt-2 text-sm text-red-400 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="description"
                    class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span>Problem Description</span>
                    <span class="text-red-400">*</span>
                </label>
                <textarea id="description" name="description" rows="5" required
                    placeholder="Describe your problem in detail:
- What happened?
- When did the problem start?
- What steps have you tried?"
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none">{{ old('description') }}</textarea>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-slate-500">minimum 10 character</p>
                    <p class="text-xs text-slate-500"><span id="charCount">0</span> / 500</p>
                </div>
                @error('description')
                    <p class="mt-2 text-sm text-red-400 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span>Attachment (Optional)</span>
                </label>
                <!-- Tempat semua attachment -->
                <div id="attachmentContainer"></div>
                <!-- Pesan limit -->
                <p id="limitMessage" class="text-red-400 text-sm mt-2 hidden">
                    Maximal 3 attachment.
                </p>
                <p id="minimumMessage" class="text-red-400 text-sm mt-2 hidden">
                    Minimun 1 attachment.
                </p>
                <!-- Tombol tambah -->
                <button type="button" id="btnAddAttachment"
                    class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm"
                    onclick="addAttachment()">
                    + Add Attachment
                </button>
                <button type="button" id="btnEraseAttachment"
                    class="mt-3 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm"
                    onclick="eraseAttachment()">
                    - Erase Attachment
                </button>
                @error('attachments')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            {{-- Action Buttons --}}
            <div class="flex space-x-3 pt-4">
                <a href="{{ route('dashboard') }}"
                    class="flex-1 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Abort</span>
                </a>
                {{-- <button type="submit"
                    class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Send Ticket</span>
                </button> --}}
                <button
        type="submit"
        id="submitBtn"
        data-loading="Sending..."
        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600
               hover:from-blue-700 hover:to-cyan-700
               text-white font-semibold rounded-xl
               shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50
               transition-all duration-300 transform
               hover:scale-[1.02] active:scale-[0.98]
               flex items-center justify-center space-x-2"
    >
        <svg id="submitIcon" class="w-5 h-5" fill="none"
             stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span id="submitText">Send Ticket</span>
    </button>

    <p id="uploadInfo"
   class="hidden text-sm text-slate-400 text-center mt-3">
    ⏳ File is being uploaded, please do not close the page...
</p>


            </div>
        </form>
    </div>
    <!-- Modal pilih sumber -->
    <div id="sourceModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
        <div class="bg-slate-900 rounded-xl p-6 w-80 text-center">
            <h3 class="text-lg font-semibold mb-4 text-white">Pilih Sumber</h3>

            <button onclick="openCamera()"
                class="w-full mb-3 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                Open Camera
            </button>

            <button onclick="openFilePicker()"
                class="w-full mb-3 px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white">
                Upload Files
            </button>
            <button onclick="closeModal()" class="w-full px-4 py-2 text-slate-400 hover:text-white">
                Abort
            </button>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('ticketForm');
    const btn = document.getElementById('submitBtn');
    const text = document.getElementById('submitText');
    const icon = document.getElementById('submitIcon');
    const uploadInfo = document.getElementById('uploadInfo');

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // ⛔ stop submit sementara

        // disable button
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');

        // ganti text
        text.textContent = btn.dataset.loading;

        // ganti icon jadi spinner
        icon.innerHTML = `
            <circle class="opacity-25" cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v4
                   a4 4 0 00-4 4H4z"></path>
        `;
        icon.classList.add('animate-spin');

        // tampilkan info upload
        uploadInfo.classList.remove('hidden');

        // ✅ kasih waktu browser render UI dulu
        requestAnimationFrame(() => {
            form.submit(); // 🚀 lanjut submit asli
        });
    });
});
</script>


{{-- <script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('ticketForm');
    const btn = document.getElementById('submitBtn');
    const text = document.getElementById('submitText');
    const icon = document.getElementById('submitIcon');

    if (!form) return;

    form.addEventListener('submit', () => {
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');

        text.textContent = btn.dataset.loading;

        icon.innerHTML = `
            <circle class="opacity-25" cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v4
                   a4 4 0 00-4 4H4z"></path>
        `;
        icon.classList.add('animate-spin');
    });
});
</script> --}}


    <script>
        // Character counter for description
        const description = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        description.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        let attachmentCount = 0;
        const maxAttachment = 3;
        const minAttachment = 1;
        // Jalankan saat halaman selesai load
        document.addEventListener("DOMContentLoaded", function() {
            initAttachment();
        });
        // Tambahkan 1 attachment default
        function initAttachment() {
            if (attachmentCount < minAttachment) {
                addAttachment();
            }
        }

        function addAttachment() {
            if (attachmentCount >= maxAttachment) return;

            attachmentCount++;
            const id = "attachment_" + attachmentCount;

            const html = `
    <div class="relative mb-3" id="wrap_${id}">
        <input type="file"
            id="${id}"
            name="attachments[]"
            accept="image/*,.pdf,.doc,.docx"
            class="hidden"
            onchange="updateFileName('${id}')">

        <label onclick="showSourceModal('${id}')"
            class="flex items-center justify-center w-full px-4 py-8 bg-slate-800 border-2 border-dashed border-slate-700 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-slate-800/50 transition-all duration-200 group">
            <div class="text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-600 group-hover:text-blue-500"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>

                <p id="fileName_${id}"
                    class="text-sm font-medium text-slate-400 group-hover:text-blue-400">
                    Click to upload file
                </p>

                <p class="text-xs text-slate-600 mt-1">
                    PNG, JPG, PDF, DOC (Max. 5MB)
                </p>
            </div>
        </label>
    </div>
    `;

            document.getElementById("attachmentContainer")
                .insertAdjacentHTML("beforeend", html);

            if (attachmentCount >= maxAttachment) {
                btnAddAttachment.disabled = true;
                btnAddAttachment.classList.add("opacity-50", "cursor-not-allowed");
                limitMessage.classList.remove("hidden");
            }
        }
        let activeInputId = null;

        function showSourceModal(id) {
            activeInputId = id;
            sourceModal.classList.remove('hidden');
            sourceModal.classList.add('flex');
        }

        function closeModal() {
            sourceModal.classList.add('hidden');
            sourceModal.classList.remove('flex');
            activeInputId = null;
        }

        function openCamera() {
            if (!activeInputId) return;

            const input = document.getElementById(activeInputId);
            input.setAttribute('capture', 'environment');
            input.click();
            closeModal();
        }

        function openFilePicker() {
            if (!activeInputId) return;
            const input = document.getElementById(activeInputId);
            input.removeAttribute('capture');
            input.click();
            closeModal();
        }

        function eraseAttachment() {
            if (attachmentCount <= minAttachment) return;
            const lastId = "wrap_attachment_" + attachmentCount;
            const lastElement = document.getElementById(lastId);
            if (lastElement) {
                lastElement.remove();
                attachmentCount--;
            }
            if (attachmentCount < maxAttachment) {
                const btnAdd = document.getElementById("btnAddAttachment");
                btnAdd.disabled = false;
                btnAdd.classList.remove("opacity-50", "cursor-not-allowed");
                document.getElementById("limitMessage").classList.add("hidden");
            }
        }

        function updateFileName(id) {
            const input = document.getElementById(id);
            const label = document.getElementById("fileName_" + id);
            label.textContent = input.files.length > 0 ?
                input.files[0].name :
                "Click to upload file";
        }
    </script>
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "3000"
        };
        @if (session('success'))
            toastr.success(@json(session('success')));
        @endif

        @if (session('error'))
            toastr.error(@json(session('error')));
        @endif
    </script>
@endsection