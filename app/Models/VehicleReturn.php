<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

        // ✅ Tambahkan ini — tabel tidak punya updated_at
    const UPDATED_AT = null;


    protected $fillable = [
        'loan_request_id',
        'returned_at',
        'received_by',
        'odometer_km_end',
        'return_note',
        'vehicle_condition',
        'anggaran_digunakan',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'odometer_km_end' => 'integer',
        'created_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the loan request for this return
     */
    public function loanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
    }

    /**
     * Get the user (Admin GA) who received this return
     */
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Alias: receiver relationship
     */
    public function receiver()
    {
        return $this->receivedBy();
    }

    /**
     * Get all expenses for this return
     */
    public function expenses()
    {
        return $this->hasMany(ReturnExpense::class, 'return_id');
    }

    /**
     * Get all attachments for this return
     */
    public function attachments()
    {
        return $this->hasMany(ReturnAttachment::class, 'return_id');
    }

    /**
     * Get the vehicle through loan request and assignment
     */
    public function vehicle()
    {
        return $this->hasOneThrough(
            Vehicle::class,
            LoanAssignment::class,
            'loan_request_id',    // Foreign key on loan_assignments
            'id',                  // Foreign key on vehicles
            'loan_request_id',    // Local key on returns
            'assigned_vehicle_id' // Local key on loan_assignments
        );
    }

    /**
     * Get the requester through loan request
     */
    public function requester()
    {
        return $this->hasOneThrough(
            User::class,
            LoanRequest::class,
            'id',              // Foreign key on loan_requests
            'id',              // Foreign key on users
            'loan_request_id', // Local key on returns
            'requester_id'     // Local key on loan_requests
        );
    }

    // ==================== SCOPES ====================

    /**
     * Scope by loan request
     */
    public function scopeByLoanRequest($query, $loanRequestId)
    {
        return $query->where('loan_request_id', $loanRequestId);
    }

    /**
     * Scope by receiver
     */
    public function scopeByReceiver($query, $userId)
    {
        return $query->where('received_by', $userId);
    }

    /**
     * Scope with expenses
     */
    public function scopeWithExpenses($query)
    {
        return $query->whereHas('expenses');
    }

    /**
     * Scope without expenses
     */
    public function scopeWithoutExpenses($query)
    {
        return $query->whereDoesntHave('expenses');
    }

    /**
     * Scope with attachments
     */
    public function scopeWithAttachments($query)
    {
        return $query->whereHas('attachments');
    }

    /**
     * Scope without attachments
     */
    public function scopeWithoutAttachments($query)
    {
        return $query->whereDoesntHave('attachments');
    }

    /**
     * Scope recent returns
     */
    public function scopeRecentFirst($query)
    {
        return $query->orderBy('returned_at', 'desc');
    }

    /**
     * Scope oldest returns
     */
    public function scopeOldestFirst($query)
    {
        return $query->orderBy('returned_at', 'asc');
    }

    /**
     * Scope by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('returned_at', [$startDate, $endDate]);
    }

    /**
     * Scope returns in specific month
     */
    public function scopeInMonth($query, $year, $month)
    {
        return $query->whereYear('returned_at', $year)
                     ->whereMonth('returned_at', $month);
    }

    /**
     * Scope returns with high expenses (above threshold)
     */
    public function scopeWithHighExpenses($query, $threshold = 1000000)
    {
        return $query->whereHas('expenses', function($q) use ($threshold) {
            $q->havingRaw('SUM(amount) > ?', [$threshold]);
        });
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get total expenses
     */
    public function getTotalExpenses(): float
    {
        return (float) $this->expenses()->sum('amount');
    }

    /**
     * Get formatted total expenses
     */
    public function getFormattedTotalExpenses(): string
    {
        return 'Rp ' . number_format($this->getTotalExpenses(), 0, ',', '.');
    }

    /**
     * Check if return has expenses
     */
    public function hasExpenses(): bool
    {
        return $this->expenses()->exists();
    }

    /**
     * Check if return has attachments
     */
    public function hasAttachments(): bool
    {
        return $this->attachments()->exists();
    }

    /**
     * Get expenses by type
     */
    public function getExpensesByType(string $type): float
    {
        return (float) $this->expenses()
            ->where('type', $type)
            ->sum('amount');
    }

    /**
     * Get formatted expenses by type
     */
    public function getFormattedExpensesByType(string $type): string
    {
        return 'Rp ' . number_format($this->getExpensesByType($type), 0, ',', '.');
    }

    /**
     * Get expenses breakdown by type
     */
    public function getExpensesBreakdown(): array
    {
        return [
            'fuel' => $this->getExpensesByType(ReturnExpense::TYPE_FUEL),
            'toll' => $this->getExpensesByType(ReturnExpense::TYPE_TOLL),
            'parking' => $this->getExpensesByType(ReturnExpense::TYPE_PARKING),
            'repair' => $this->getExpensesByType(ReturnExpense::TYPE_REPAIR),
            'other' => $this->getExpensesByType(ReturnExpense::TYPE_OTHER),
        ];
    }

    /**
     * Get receiver name
     */
    public function getReceiverName(): string
    {
        return $this->receivedBy ? $this->receivedBy->full_name : 'Unknown';
    }

    /**
     * Get formatted returned date
     */
    public function getFormattedReturnedAt(): string
    {
        return $this->returned_at ? $this->returned_at->format('d M Y H:i') : '-';
    }

    /**
     * Get return note or default
     */
    public function getReturnNote(): string
    {
        return $this->return_note ?? '-';
    }

    /**
     * Get formatted odometer
     */
    public function getFormattedOdometer(): string
    {
        return number_format($this->odometer_km_end, 0, ',', '.') . ' km';
    }

    /**
     * Get vehicle info
     */
    public function getVehicleInfo(): ?string
    {
        $assignment = $this->loanRequest?->assignment;
        if (!$assignment || !$assignment->assignedVehicle) {
            return null;
        }

        return $assignment->getVehicleName();
    }

    /**
     * Get requester name
     */
    public function getRequesterName(): string
    {
        return $this->loanRequest?->requester?->full_name ?? 'Unknown';
    }

    /**
     * Get requester unit name
     */
    public function getRequesterUnitName(): string
    {
        return $this->loanRequest?->unit?->name ?? 'Unknown';
    }

    /**
     * Calculate distance traveled (if start odometer available)
     */
    public function getDistanceTraveled(): ?int
    {
        // This would require odometer_km_start in returns table or from vehicle
        // For now, return null as placeholder
        return null;
    }

    /**
     * Get expenses count
     */
    public function getExpensesCount(): int
    {
        return $this->expenses()->count();
    }

    /**
     * Get attachments count
     */
    public function getAttachmentsCount(): int
    {
        return $this->attachments()->count();
    }

    /**
     * Get return proofs count
     */
    public function getReturnProofsCount(): int
    {
        return $this->attachments()
            ->where('type', ReturnAttachment::TYPE_RETURN_PROOF)
            ->count();
    }

    /**
     * Get expense receipts count
     */
    public function getExpenseReceiptsCount(): int
    {
        return $this->attachments()
            ->where('type', ReturnAttachment::TYPE_EXPENSE_RECEIPT)
            ->count();
    }

    /**
     * Check if return has notes
     */
    public function hasNotes(): bool
    {
        return !is_null($this->return_note) && trim($this->return_note) !== '';
    }

    /**
     * Check if expenses are documented (has receipts)
     */
    public function hasExpenseReceipts(): bool
    {
        if (!$this->hasExpenses()) {
            return false;
        }

        return $this->attachments()
            ->where('type', ReturnAttachment::TYPE_EXPENSE_RECEIPT)
            ->exists();
    }

    /**
     * Get return summary
     */
    public function getSummary(): string
    {
        $vehicle = $this->getVehicleInfo() ?? 'Unknown Vehicle';
        $requester = $this->getRequesterName();
        $date = $this->getFormattedReturnedAt();
        $expenses = $this->getFormattedTotalExpenses();
        
        return "{$vehicle} | {$requester} | {$date} | Expenses: {$expenses}";
    }

    /**
     * Get return details array
     */
    public function getDetails(): array
    {
        return [
            'id' => $this->id,
            'loan_request_id' => $this->loan_request_id,
            'vehicle_info' => $this->getVehicleInfo(),
            'requester_name' => $this->getRequesterName(),
            'requester_unit' => $this->getRequesterUnitName(),
            'receiver_name' => $this->getReceiverName(),
            'returned_at' => $this->getFormattedReturnedAt(),
            'odometer_km_end' => $this->odometer_km_end,
            'odometer_formatted' => $this->getFormattedOdometer(),
            'return_note' => $this->getReturnNote(),
            'has_notes' => $this->hasNotes(),
            'total_expenses' => $this->getTotalExpenses(),
            'total_expenses_formatted' => $this->getFormattedTotalExpenses(),
            'expenses_breakdown' => $this->getExpensesBreakdown(),
            'has_expenses' => $this->hasExpenses(),
            'expenses_count' => $this->getExpensesCount(),
            'has_attachments' => $this->hasAttachments(),
            'attachments_count' => $this->getAttachmentsCount(),
            'return_proofs_count' => $this->getReturnProofsCount(),
            'expense_receipts_count' => $this->getExpenseReceiptsCount(),
            'has_expense_receipts' => $this->hasExpenseReceipts(),
        ];
    }

    /**
     * Check if return is complete (has all required data)
     */
    public function isComplete(): bool
    {
        return $this->returned_at 
            && $this->received_by
            && $this->odometer_km_end > 0;
    }

    /**
     * Get completion percentage (based on optional fields)
     */
    public function getCompletionPercentage(): int
    {
        $completed = 0;
        $total = 5;

        if ($this->returned_at) $completed++;
        if ($this->received_by) $completed++;
        if ($this->odometer_km_end > 0) $completed++;
        if ($this->hasNotes()) $completed++;
        if ($this->hasAttachments()) $completed++;

        return (int) (($completed / $total) * 100);
    }
}
