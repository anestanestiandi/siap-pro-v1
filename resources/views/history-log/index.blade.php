<x-app-layout>
    <div class="pt-0 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                <form action="{{ route('history-log-activity') }}" method="GET"
                    class="flex flex-col lg:flex-row gap-4 items-center">

                    {{-- Activity Type --}}
                    <div class="w-full lg:w-40">
                        <select name="type" onchange="this.form.submit()"
                            class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg">
                            <option value="all">All Types</option>
                            <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('type') == 'logout' ? 'selected' : '' }}>Logout</option>
                            <option value="create" {{ request('type') == 'create' ? 'selected' : '' }}>Create</option>
                            <option value="update" {{ request('type') == 'update' ? 'selected' : '' }}>Update</option>
                            <option value="delete" {{ request('type') == 'delete' ? 'selected' : '' }}>Delete</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="w-full lg:w-40">
                        <select name="status" onchange="this.form.submit()"
                            class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg">
                            <option value="all">All Status</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success
                            </option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    {{-- Date Range --}}
                    <div class="w-full lg:w-44">
                        <select name="date" onchange="this.form.submit()"
                            class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg">
                            <option value="all">All Time</option>
                            <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request('date') == 'yesterday' ? 'selected' : '' }}>Yesterday
                            </option>
                            <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>Last 30 Days</option>
                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="relative flex-1 w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search activity...">
                    </div>

                    {{-- Reset Button --}}
                    <div class="w-full lg:w-auto">
                        <a href="{{ route('history-log-activity') }}"
                            class="inline-flex items-center justify-center w-full lg:w-auto gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition shadow-sm whitespace-nowrap border border-gray-200">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Timestamp</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Activity</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Details</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($logs as $log)
                                <tr>
                                    {{-- Timestamp --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="font-medium text-gray-900">{{ $log->created_at->format('Y-m-d') }}</div>
                                        <div class="text-xs">{{ $log->created_at->format('H:i:s') }}</div>
                                    </td>

                                    {{-- User --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                                                {{ $log->user ? strtoupper(substr($log->user->nama_lengkap, 0, 1)) : '?' }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $log->user->nama_lengkap ?? 'System' }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $log->user->username ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Activity --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $colors = [
                                                'login' => 'bg-blue-100 text-blue-800',
                                                'logout' => 'bg-red-100 text-red-800',
                                                'create' => 'bg-green-100 text-green-800',
                                                'update' => 'bg-yellow-100 text-yellow-800',
                                                'delete' => 'bg-red-100 text-red-800',
                                            ];
                                            $color = $colors[$log->action] ?? 'bg-indigo-100 text-indigo-800';

                                            $icons = [
                                                'login' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>',
                                                'logout' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>',
                                                'create' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>',
                                                'update' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>',
                                                'delete' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>',
                                            ];
                                            $icon = $icons[$log->action] ?? '';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {!! $icon !!}
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>

                                    {{-- Details --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 truncate max-w-xs"
                                            title="{{ $log->description }}">
                                            {{ $log->description }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($log->status === 'success')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Success
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Failed
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="mt-2 text-sm font-medium">Belum ada aktivitas yang tercatat</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $logs->links('vendor.pagination.custom') }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
