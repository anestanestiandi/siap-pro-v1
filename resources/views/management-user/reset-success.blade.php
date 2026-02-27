<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Password Reset Success') }}
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

                        {{-- Step 2: Reset (Completed) --}}
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-[#3B5286] text-white flex items-center justify-center font-bold text-sm">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-900 mt-2">RESET</span>
                        </div>

                        {{-- Line --}}
                        <div class="flex-1 h-0.5 bg-[#3B5286] mx-2 -mt-4"></div>

                        {{-- Step 3: Success (Active/Completed) --}}
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-[#3B5286] text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-blue-50">
                                3
                            </div>
                            <span class="text-xs font-bold text-[#3B5286] mt-2">SUCCESS</span>
                        </div>
                    </div>
                </div>

                {{-- Success Content --}}
                <div class="p-8 text-center">
                    <div class="mb-6 flex justify-center">
                        <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center">
                            <svg class="w-10 h-10 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Password Successfully Updated</h1>
                    <p class="text-gray-500 mb-8 px-8">
                        The password for <span class="font-semibold text-gray-800">{{ $user->nama_lengkap }}</span> has
                        been changed. They can now log in using their new password.
                    </p>

                    <a href="{{ route('management-user.index') }}"
                        class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-[#3B5286] hover:bg-[#2E4068] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Back to User Management
                        <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
