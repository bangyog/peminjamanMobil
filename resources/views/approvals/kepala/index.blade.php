<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Persetujuan Kepala Departemen</h2>
                <p class="text-sm text-gray-500 mt-0.5">Pengajuan yang menunggu tanda tangan Anda</p>
            </div>
            <span class="text-xs text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- Counter --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 bg-yellow-50 border border-yellow-200 px-4 py-2 rounded-lg">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-yellow-500"></span>
                    </span>
                    <span class="text-sm font-semibold text-yellow-800">
                        {{ $pendingRequests->count() }} pengajuan menunggu persetujuan
                    </span>
                </div>
            </div>

            {{-- List Pengajuan --}}
            @if($pendingRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingRequests as $req)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-yellow-300 hover:shadow-md transition overflow-hidden">

                        {{-- Header card --}}
                        <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-mono text-gray-400">#{{ $req->id }}</span>
                                <span class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</span>
                                <span class="text-xs text-gray-500">·</span>
                                <span class="text-xs text-gray-500">{{ $req->requester->unit->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                    ⏳ Menunggu Kepala
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $req->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        {{-- Body card --}}
                        <div class="p-5">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Keperluan</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ $req->purpose }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Tujuan</p>
                                    <p class="text-sm text-gray-700">{{ $req->destination ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Berangkat</p>
                                    <p class="text-sm text-gray-700">
                                        {{ $req->depart_at?->format('d M Y H:i') ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Rencana Kembali</p>
                                    <p class="text-sm text-gray-700">
                                        {{ $req->expected_return_at?->format('d M Y H:i') ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Dari Lokasi</p>
                                    <p class="text-sm text-gray-700">{{ $req->siap_di ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Kembali Ke</p>
                                    <p class="text-sm text-gray-700">{{ $req->kembali_di ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Kota Pengajuan</p>
                                    <p class="text-sm text-gray-700">{{ $req->request_city ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Preferensi Kendaraan</p>
                                    @if($req->preferredVehicle)
                                        <p class="text-sm text-gray-700">
                                            🚗 {{ $req->preferredVehicle->brand }} {{ $req->preferredVehicle->model }}
                                            <span class="font-mono text-xs text-gray-400">
                                                ({{ $req->preferredVehicle->plate_no }})
                                            </span>
                                        </p>
                                    @elseif($req->requested_vehicle_text)
                                        <p class="text-sm text-gray-700">{{ $req->requested_vehicle_text }}</p>
                                    @else
                                        <p class="text-sm text-gray-400 italic">Tidak ada preferensi</p>
                                    @endif
                                </div>
                            </div>

                            @if($req->notes)
                            <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                <p class="text-xs text-blue-600 font-medium mb-1">📝 Catatan:</p>
                                <p class="text-sm text-blue-800">{{ $req->notes }}</p>
                            </div>
                            @endif

                            {{-- Tanda tangan pemohon --}}
                            @if($req->requester_signature)
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-1">Tanda Tangan Pemohon:</p>
                                <img src="{{ Storage::url($req->requester_signature) }}"
                                     alt="TTD Pemohon"
                                     class="h-16 border border-gray-200 rounded-lg bg-white p-1">
                            </div>
                            @endif
                        </div>

                        {{-- Footer / Actions --}}
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                            <a href="{{ route('approvals.kepala.approve.form', $req) }}"
                               class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Review & Putuskan
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
                    <svg class="w-14 h-14 text-green-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 font-semibold">Semua pengajuan sudah diproses!</p>
                    <p class="text-sm text-gray-400 mt-1">Tidak ada pengajuan yang menunggu persetujuan Anda</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
