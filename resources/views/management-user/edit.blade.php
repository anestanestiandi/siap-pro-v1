<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="pt-0 pb-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 bg-[#3B5286] border-b border-[#2E4068]">
                    <h3 class="text-lg font-medium text-white">Edit User</h3>
                    <p class="mt-1 text-sm text-blue-200">Ubah data pengguna.</p>
                </div>

                <form action="{{ route('management-user.update', $user->id_user) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role & Jenis Kelamin --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Role --}}
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                required>
                                <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin
                                </option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>

                                <option value="eksternal" {{ old('role', $user->role) == 'eksternal' ? 'selected' : '' }}>Eksternal
                                </option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <div class="flex items-center gap-6 mt-1.5">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="jenis_kelamin" value="L"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="text-sm text-gray-700">Laki-laki</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="jenis_kelamin" value="P"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'checked' : '' }}
                                        class="text-pink-600 focus:ring-pink-500 border-gray-300">
                                    <span class="text-sm text-gray-700">Perempuan</span>
                                </label>
                            </div>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Status Aktif --}}
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700">Status Akun</label>
                        <select name="is_active" id="is_active"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                            <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Tidak Aktif (Suspend)</option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-between pt-4 border-t border-gray-100">
                        {{-- Reset Password Button (Optional in Edit) --}}
                         <a href="{{ route('management-user.reset-password-form', $user->id_user) }}" 
                            class="text-amber-600 hover:text-amber-800 text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                            </svg>
                            Reset Password
                        </a>

                        <div class="flex gap-3">
                            <a href="{{ route('management-user.index') }}"
                                class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-[#3B5286] text-white rounded-lg text-sm font-bold hover:bg-[#2E4068] transition shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
                

            </div>
        </div>
    </div>
</x-app-layout>
