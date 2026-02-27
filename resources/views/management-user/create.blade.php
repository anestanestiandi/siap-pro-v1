<x-app-layout :hideHeader="true">
    {{-- Main Container --}}
    <div class="max-w-3xl mx-auto" x-data="{ showCancelModal: false }">

        {{-- Header Section --}}
        <div class="bg-[#3B5286] rounded-t-xl p-6 text-white">
            <h1 class="text-xl font-bold">Tambah User Baru</h1>
            <p class="text-sm text-blue-100 mt-1">Isi formulir di bawah untuk menambahkan pengguna aplikasi.</p>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-b-xl shadow-lg">
            <form action="{{ route('management-user.store') }}" method="POST" class="p-8 space-y-6"
                x-data="{ 
                    password: '',
                    showPw: false,
                    showConfirm: false,
                    get hasLength() { return this.password.length >= 8 },
                    get hasUpper() { return /[A-Z]/.test(this.password) },
                    get hasNumber() { return /[0-9]/.test(this.password) },
                    get hasSpecial() { return /[!@#$%^&*(),.?\&quot;:{}|<>]/.test(this.password) }
                }">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Nama Lengkap
                    </label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="Masukkan nama lengkap" required>
                    @error('nama_lengkap')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        Username
                    </label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="Contoh: budi_santoso" required>
                    @error('username')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role & Jenis Kelamin --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Role
                        </label>
                        <select name="role" id="role"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="">Pilih Role</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="eksternal" {{ old('role') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            Jenis Kelamin
                        </label>
                        <div class="flex items-center gap-6 mt-1.5">
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 hover:bg-blue-50 transition">
                                <input type="radio" name="jenis_kelamin" value="L"
                                    {{ old('jenis_kelamin', 'L') == 'L' ? 'checked' : '' }}
                                    class="text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-sm text-gray-700 font-medium">Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 hover:bg-pink-50 transition">
                                <input type="radio" name="jenis_kelamin" value="P"
                                    {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}
                                    class="text-pink-600 focus:ring-pink-500 border-gray-300">
                                <span class="text-sm text-gray-700 font-medium">Perempuan</span>
                            </label>
                        </div>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Password
                        </label>
                        <div class="relative">
                            <input :type="showPw ? 'text' : 'password'" name="password" id="password" x-model="password"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10"
                                placeholder="Min. 8 karakter" required>
                            <button type="button" @click="showPw = !showPw"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg x-show="!showPw" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z" />
                                </svg>
                                <svg x-show="showPw" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#3B5286]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10"
                                placeholder="Ulangi password" required>
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg x-show="!showConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z" />
                                </svg>
                                <svg x-show="showConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Password Requirements --}}
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Password Requirements</h4>
                    <ul class="space-y-1.5">
                        {{-- Length --}}
                        <li class="flex items-center gap-2 text-xs transition-colors duration-200" :class="hasLength ? 'text-green-600 font-medium' : 'text-gray-400'">
                            <template x-if="hasLength">
                                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </template>
                            <template x-if="!hasLength">
                                <div class="w-4 h-4 rounded-full border-2 border-gray-300"></div>
                            </template>
                            At least 8 characters
                        </li>
                        {{-- Uppercase --}}
                        <li class="flex items-center gap-2 text-xs transition-colors duration-200" :class="hasUpper ? 'text-green-600 font-medium' : 'text-gray-400'">
                            <template x-if="hasUpper">
                                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </template>
                            <template x-if="!hasUpper">
                                <div class="w-4 h-4 rounded-full border-2 border-gray-300"></div>
                            </template>
                            Include at least one uppercase letter
                        </li>
                        {{-- Number --}}
                        <li class="flex items-center gap-2 text-xs transition-colors duration-200" :class="hasNumber ? 'text-green-600 font-medium' : 'text-gray-400'">
                            <template x-if="hasNumber">
                                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </template>
                            <template x-if="!hasNumber">
                                <div class="w-4 h-4 rounded-full border-2 border-gray-300"></div>
                            </template>
                            Include at least one number
                        </li>
                        {{-- Special --}}
                        <li class="flex items-center gap-2 text-xs transition-colors duration-200" :class="hasSpecial ? 'text-green-600 font-medium' : 'text-gray-400'">
                            <template x-if="hasSpecial">
                                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </template>
                            <template x-if="!hasSpecial">
                                <div class="w-4 h-4 rounded-full border-2 border-gray-300"></div>
                            </template>
                            Include at least one special character
                        </li>
                    </ul>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="showCancelModal = true"
                        class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#3B5286] text-white rounded-lg text-sm font-bold hover:bg-[#2E4068] transition shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan User
                    </button>
                </div>
            </form>
        </div>

        {{-- Cancellation Modal --}}
        <div x-show="showCancelModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative"
                 @click.outside="showCancelModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-95 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="scale-100 opacity-100"
                 x-transition:leave-end="scale-95 opacity-0">
                
                {{-- Close Button --}}
                <button type="button" @click="showCancelModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Modal Content --}}
                <div class="text-center sm:text-left">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Batalkan Pembuatan User?
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8">
                        Data yang sudah Anda isi tidak akan tersimpan jika Anda membatalkan proses ini.
                    </p>

                    {{-- Modal Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('management-user.index') }}" 
                           class="flex-1 px-6 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition text-center">
                            Ya, Batalkan
                        </a>
                        <button type="button" @click="showCancelModal = false"
                                class="flex-1 px-6 py-2.5 bg-[#3B5286] text-white rounded-xl font-semibold hover:bg-[#2E4068] transition shadow-lg shadow-blue-900/20">
                            Tidak, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
