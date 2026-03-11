<x-app-layout>
    <div class="pt-0 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Title and Back button are now in the layout header --}}

            <form action="{{ route('management-user.update-display-settings') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach(['super_admin', 'admin', 'eksternal'] as $role)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg 
                                    @if($role == 'super_admin') bg-purple-100 text-purple-600 
                                    @elseif($role == 'admin') bg-blue-100 text-blue-600 
                                    @else bg-yellow-100 text-yellow-600 @endif">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900">{{ ucwords(str_replace('_', ' ', $role)) }}</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex items-center justify-between group">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900 transition-colors">Kolom File</span>
                                    <span class="text-xs text-gray-500">Tampilkan lampiran file dokumen</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="{{ $role }}_file" class="sr-only peer" {{ ($settings[$role]['file'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#3B5286]"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between group">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900 transition-colors">Kolom Last Update</span>
                                    <span class="text-xs text-gray-500">Tampilkan info pembaruan terakhir</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="{{ $role }}_last_update" class="sr-only peer" {{ ($settings[$role]['last_update'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#3B5286]"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-[#3B5286] hover:bg-[#2E4068] text-white font-bold rounded-xl transition shadow-lg hover:shadow-xl transform active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
