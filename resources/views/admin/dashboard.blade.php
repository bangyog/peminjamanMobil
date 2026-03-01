<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Banner -->
            <div class="mb-8 bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-8 sm:px-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white">
                                Selamat datang, Admin GA! 👋
                            </h1>
                            <p class="mt-2 text-blue-100">
                                {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                            </p>
                        </div>
                        <div class="hidden lg:block">
                            <a href="{{ route('admin.loan-requests.index') }}"
                                class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg shadow-md hover:bg-blue-50 transition flex items-center">

                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">

                <!-- Total Users -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Users</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-green-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                                +3% dari bulan lalu
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Kendaraan -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Kendaraan</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_vehicles'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-500">
                                📊 Update terakhir hari ini
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tersedia -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Tersedia</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['available_vehicles'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full"
                                    @style([ 'width: ' . ($stats['available_percentage'] ?? 0) . '%'
                                    ])>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $stats['available_percentage'] ?? 0 }}% dari total</p>
                        </div>
                    </div>
                </div>

                <!-- Perlu Approval -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Perlu Approval</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_requests'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            @if(($stats['pending_requests'] ?? 0) > 0)
                            <p class="text-xs text-yellow-600 flex items-center">
                                ⚠️ Butuh perhatian
                            </p>
                            @else
                            <p class="text-xs text-green-600 flex items-center">
                                ✅ Semua clear
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sedang Dipinjam -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Sedang Dipinjam</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['active_loans'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-blue-600">
                                📍 Real-time data
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Main Content: 2 Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Left: Pengajuan Perlu Approval -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                                Pengajuan Perlu Approval
                            </h3>
                            <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">
                                {{ $pendingRequests->count() }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($pendingRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($pendingRequests as $request)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 hover:border-blue-300 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-blue-600 font-semibold text-sm">
                                                    {{ strtoupper(substr($request->requester->full_name ?? 'U', 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $request->requester->full_name ?? 'Unknown' }}</h4>
                                                <p class="text-xs text-gray-500">
                                                    {{ $request->unit->name ?? 'N/A' }} • {{ $request->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-700 ml-13">
                                            {{ Str::limit($request->purpose ?? 'Tidak ada keterangan', 60) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-3 ml-13">
                                    <a href="{{ route('admin.loan-requests.show', $request->id) }}"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                        Review
                                    </a>
                                    <a href="{{ route('admin.loan-requests.show', $request->id) }}"
                                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                        Detail
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.loan-requests.index') }}?status=pending"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Lihat Semua Pengajuan →
                            </a>
                        </div>
                        @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-4 text-gray-500">Tidak ada pengajuan yang perlu di-review</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Peminjaman Aktif -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">🚗 Peminjaman Aktif</h2>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ $activeLoans->count() }} Aktif
                        </span>
                    </div>

                    @if($activeLoans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kendaraan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($activeLoans as $loan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $loan->requester->full_name ?? 'Unknown' }}</div>
                                        <div class="text-sm text-gray-500">{{ $loan->requester->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $loan->unit->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($loan->assignment && $loan->assignment->vehicle)
                                        <div class="font-medium text-gray-900">
                                            {{ $loan->assignment->vehicle->brand ?? '' }} {{ $loan->assignment->vehicle->model ?? '' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $loan->assignment->vehicle->license_plate ?? '' }}
                                        </div>
                                        @else
                                        <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        @if($loan->depart_at)
                                        <div>{{ $loan->depart_at->format('d/m/Y H:i') }}</div>
                                        @if($loan->expected_return_at)
                                        <div class="text-xs text-gray-400">s/d {{ $loan->expected_return_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                        @else
                                        <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($loan->status == 'approved')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Disetujui</span>
                                        @elseif($loan->status == 'in_use')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Sedang Digunakan</span>
                                        @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">{{ ucfirst($loan->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <!-- Detail Button -->
                                            <a href="{{ route('admin.loan-requests.show', $loan) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Detail
                                            </a>

                                            <!-- ✅ DOWNLOAD PDF BUTTON (FIXED ROUTE) -->
                                            <a href="{{ route('admin.dashboard.download-pdf', $loan->id) }}"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium inline-flex items-center gap-1"
                                                title="Download PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="mt-2">Tidak ada peminjaman aktif</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>