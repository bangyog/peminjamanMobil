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
            'returned_at'            => 'required|date',
            'odometer_km_end'        => 'required|integer|min:0',
            'anggaran_digunakan'          => 'nullable|numeric|min:0',
            'return_note'            => 'nullable|string|max:1000',
            'expenses'               => 'nullable|array',
            'expenses.*.type'        => 'nullable|in:fuel,toll,parking,repair,other',
            'expenses.*.amount'      => 'nullable|numeric|min:0',
            'expenses.*.description' => 'nullable|string|max:255',
            'expenses.*.receipt'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::transaction(function () use ($request, $loanRequest, $user) {

            // ✅ Step 1: Insert ke tabel returns
            $vehicleReturn = VehicleReturn::create([
                'loan_request_id' => $loanRequest->id,
                'returned_at'     => $request->returned_at,
                'odometer_km_end' => $request->odometer_km_end,
                'anggaran_digunakan'   => $request->anggaran_digunakan,
                'return_note'     => $request->return_note,
            ]);

            // ✅ Step 2: Insert expenses + file receipt
            // Harus dipisah: input() untuk text, file() untuk file
            $expensesData  = $request->input('expenses', []);
            $expensesFiles = $request->file('expenses', []);

            foreach ($expensesData as $index => $expense) {
                if (empty($expense['amount'])) continue;

                // ✅ Upload receipt jika ada
                $receiptPath = null;
                if (isset($expensesFiles[$index]['receipt'])) {
                    $file        = $expensesFiles[$index]['receipt'];
                    $receiptPath = $file->store('return-receipts', 'public');

                    // ✅ Insert juga ke return_attachments
                    ReturnAttachment::create([
                        'return_id'       => $vehicleReturn->id,
                        'type'            => 'expense_receipt',
                        'file_name'       => $file->getClientOriginalName(),
                        'file_url'        => $receiptPath,
                        'mime_type'       => $file->getMimeType(),
                        'file_size_bytes' => $file->getSize(),
                        'uploaded_by'     => $user->id,
                    ]);
                }

                // ✅ Insert ke return_expenses
                ReturnExpense::create([
                    'return_id'   => $vehicleReturn->id,
                    'type'        => $expense['type'] ?? 'other',
                    'description' => $expense['description'] ?? null,
                    'amount'      => $expense['amount'],
                    'receipt_url' => $receiptPath,
                    'created_by'  => $user->id,
                ]);
            }

            // ✅ Step 3: Update status loan_request
            $loanRequest->update(['status' => 'returned']);
        });

        return redirect()
            ->route('loan-requests.show', $loanRequest)
            ->with('success', '✅ Pengembalian berhasil diajukan. Menunggu konfirmasi Admin GA.');
    }
}
