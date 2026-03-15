<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Verifikasi Admin HR</h2>
                <p class="text-sm text-gray-500 mt-0.5">Pengajuan yang sudah disetujui Kepala, menunggu verifikasi Anda</p>
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

            {{-- Counter --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-200 px-4 py-2 rounded-lg">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                    </span>
                    <span class="text-sm font-semibold text-indigo-800">
                        {{ $pendingRequests->count() }} pengajuan menunggu verifikasi
                    </span>
                </div>
            </div>

            {{-- Penjelasan status --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-700">
                    <p class="font-semibold mb-0.5">Alur Persetujuan:</p>
                    <p>Pengajuan di bawah ini sudah disetujui oleh <strong>Kepala Departemen</strong> (status: <code class="bg-blue-100 px-1 rounded text-xs">approved_kepala</code>)
                    dan sekarang perlu verifikasi dari Admin HR sebelum diteruskan ke Admin GA.</p>
                </div>
            </div>

            @if($pendingRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingRequests as $req)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-indigo-300 hover:shadow-md transition overflow-hidden">

                        {{-- Header --}}
                        <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-mono text-gray-400">#{{ $req->id }}</span>
                                <span class="text-sm font-bold text-gray-800">{{ $req->requester->full_name ?? '-' }}</span>
                                <span class="text-xs text-gray-500">·</span>
                                <span class="text-xs text-gray-500">{{ $req->requester->unit->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                    ✅ Disetujui Kepala
                                </span>
                                <span class="text-xs text-gray-400">{{ $req->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Body --}}
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
                                    <p class="text-sm text-gray-700">{{ $req->depart_at?->format('d M Y H:i') ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Rencana Kembali</p>
                                    <p class="text-sm text-gray-700">{{ $req->expected_return_at?->format('d M Y H:i') ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- Info approval kepala --}}
                            @if($req->approvals && $req->approvals->where('approval_level', 'kepala')->first())
                            @php $kepalApproval = $req->approvals->where('approval_level', 'kepala')->first(); @endphp
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg mb-3 flex items-center gap-3">
                                @if($kepalApproval->approver_signature)
                                    <img src="{{ Storage::url($kepalApproval->approver_signature) }}"
                                         alt="TTD Kepala" class="h-10 border border-gray-200 bg-white rounded p-0.5">
                                @endif
                                <div>
                                    <p class="text-xs text-green-600 font-semibold">
                                        ✅ Disetujui oleh: {{ $kepalApproval->approver->full_name ?? 'Kepala Departemen' }}
                                    </p>
                                    <p class="text-xs text-green-500">
                                        {{ \Carbon\Carbon::parse($kepalApproval->decided_at)->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if($req->notes)
                            <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                                <p class="text-xs text-yellow-600 font-medium mb-0.5">📝 Catatan:</p>
                                <p class="text-sm text-yellow-800">{{ $req->notes }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                            <a href="{{ route('approvals.hr.approve.form', $req) }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Review & Verifikasi
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
                    <p class="text-gray-500 font-semibold">Tidak ada pengajuan menunggu verifikasi</p>
                    <p class="text-sm text-gray-400 mt-1">Semua pengajuan sudah diverifikasi</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
