<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reset Password') }}
        </h2>
    </x-slot>

    <div class="pt-0 pb-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                {{-- Stepper Header --}}
                <div class="bg-white p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between px-8">
                        {{-- Step 1: Verification (Completed) --}}
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-[#3B5286] text-white flex items-center justify-center font-bold text-sm">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-900 mt-2">VERIFICATION</span>
                        </div>

                        {{-- Line --}}
                        <div class="flex-1 h-0.5 bg-[#3B5286] mx-2 -mt-4"></div>

                        {{-- Step 2: Reset (Active) --}}
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-[#3B5286] text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-blue-50">
                                2
                            </div>
                            <span class="text-xs font-bold text-[#3B5286] mt-2">RESET</span>
                        </div>

                        {{-- Line --}}
                        <div class="flex-1 h-0.5 bg-gray-200 mx-2 -mt-4"></div>

                        {{-- Step 3: Success (Pending) --}}
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm">
                                3
                            </div>
                            <span class="text-xs font-medium text-gray-400 mt-2">SUCCESS</span>
                        </div>
                    </div>
                </div>

                {{-- Form Content --}}
                <div class="p-8" x-data="{ 
                    password: '',
                    showPw: false,
                    showConfirm: false,
                    get hasLength() { return this.password.length >= 8 },
                    get hasUpper() { return /[A-Z]/.test(this.password) },
                    get hasNumber() { return /[0-9]/.test(this.password) },
                    get hasSpecial() { return /[!@#$%^&*(),.?\&quot;:{}|<>]/.test(this.password) }
                }">
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-bold text-gray-900">Create New Password</h1>
                        <p class="text-sm text-gray-500 mt-2 px-4">
                            Your identity has been verified. Please choose a strong password to secure the account for
                            <span class="font-semibold text-gray-800">{{ $user->nama_lengkap }}</span>.
                        </p>
                    </div>

                    <form action="{{ route('management-user.update-password', $user->id_user) }}" method="POST"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- New Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New
                                Password</label>
                            <div class="relative">
                                <input :type="showPw ? 'text' : 'password'" name="password" id="password" x-model="password"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10"
                                    placeholder="Enter new password" required>
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
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative">
                                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10"
                                    placeholder="Repeat your password" required>
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

                        {{-- Password Requirements (Dynamic for UI) --}}
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

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-[#3B5286] hover:bg-[#2E4068] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Update Password
                            <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>

                        <div class="text-center">
                            <a href="{{ route('management-user.index') }}"
                                class="text-xs text-gray-400 hover:text-gray-600 transition">Cancel and return to
                                list</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</x-app-layout>
