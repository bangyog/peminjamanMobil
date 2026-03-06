<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\VehicleReturn;
use App\Models\ReturnExpense;
use App\Models\ReturnAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    private function getAuthUser()
    {
        return Auth::user();
    }

    public function create(LoanRequest $loanRequest)
    {
        $user = $this->getAuthUser();

        if ($loanRequest->requester_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if ($loanRequest->status !== 'in_use') {
            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('error', 'Kendaraan tidak dalam status sedang digunakan.');
        }

        // ✅ return adalah reserved keyword, akses pakai {'return'}
        if ($loanRequest->{'return'}) {
            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('info', 'Pengembalian sudah pernah diajukan.');
        }

        $loanRequest->load(['unit', 'assignment.assignedVehicle']);

        return view('loan-requests.returns.create', compact('loanRequest'));
    }

    public function store(Request $request, LoanRequest $loanRequest)
    {
        $user = $this->getAuthUser();

        if ($loanRequest->requester_id !== $user->id) {
            abort(403);
        }

        if ($loanRequest->status !== 'in_use') {
            return back()->with('error', 'Kendaraan tidak dalam status sedang digunakan.');
        }

        if ($loanRequest->{'return'}) {
            return back()->with('error', 'Pengembalian sudah pernah diajukan.');
        }

        $request->validate([
            'returned_at'       => 'required|date',
            'odometer_km_end'   => 'required|integer|min:0',
            'vehicle_condition' => 'required|in:good,minor_damage,major_damage,needs_maintenance', // ✅ BARU
            'condition_notes'   => 'nullable|string|max:1000',                                     // ✅ BARU (ganti return_note)
            'attachments'       => 'nullable|array|max:5',                                         // ✅ BARU
            'attachments.*'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',               // ✅ BARU
        ], [
            'vehicle_condition.required' => 'Kondisi kendaraan harus dipilih',
            'vehicle_condition.in'       => 'Kondisi kendaraan tidak valid',
            'odometer_km_end.required'   => 'Odometer harus diisi',
            'returned_at.required'       => 'Tanggal pengembalian harus diisi',
        ]);

        DB::transaction(function () use ($request, $loanRequest, $user) {

            // ✅ Step 1: Simpan data pengembalian
            $vehicleReturn = VehicleReturn::create([
                'loan_request_id'   => $loanRequest->id,
                'returned_at'       => $request->returned_at,
                'odometer_km_end'   => $request->odometer_km_end,
                'vehicle_condition' => $request->vehicle_condition, // ✅ BARU
                'return_note'       => $request->condition_notes,   // ✅ condition_notes → return_note
            ]);

            // ✅ Step 2: Upload lampiran foto/dokumen (jika ada)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('return-attachments', 'public');

                    ReturnAttachment::create([
                        'return_id'       => $vehicleReturn->id,
                        'type'            => 'return_proof',
                        'file_name'       => $file->getClientOriginalName(),
                        'file_url'        => $path,
                        'mime_type'       => $file->getMimeType(),
                        'file_size_bytes' => $file->getSize(),
                        'uploaded_by'     => $user->id,
                    ]);
                }
            }

            // ✅ Step 3: Update status loan_request
            $loanRequest->update(['status' => 'returned']);
        });

        return redirect()
            ->route('loan-requests.show', $loanRequest)
            ->with('success', '✅ Pengembalian berhasil diajukan. Menunggu konfirmasi Admin GA.');
    }
}
