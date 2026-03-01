<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LoanStatusLog extends Model
{
    use HasFactory;

    // ✅ STATUS CONSTANTS (sesuai LoanRequest)
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED_KEPALA = 'approved_kepala';
    const STATUS_APPROVED_GA = 'approved_ga';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_IN_USE = 'in_use';
    const STATUS_RETURNED = 'returned';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'loan_request_id',
        'from_status',
        'to_status',
        'changed_by',
        'change_note',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    // ✅ Custom timestamp (pakai changed_at, bukan created_at/updated_at)
    public $timestamps = false;

    const CREATED_AT = 'changed_at';

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the loan request that owns this log
     */
    public function loanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
    }

    /**
     * Get the user who made this status change
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Alias: changer relationship
     */
    public function changer()
    {
        return $this->changedBy();
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
     * Scope by changer (user)
     */
    public function scopeByChanger($query, $userId)
    {
        return $query->where('changed_by', $userId);
    }

    /**
     * Scope by from status
     */
    public function scopeFromStatus($query, $status)
    {
        return $query->where('from_status', $status);
    }

    /**
     * Scope by to status
     */
    public function scopeToStatus($query, $status)
    {
        return $query->where('to_status', $status);
    }

    /**
     * Scope status changes to approved_kepala
     */
    public function scopeToApprovedKepala($query)
    {
        return $query->where('to_status', self::STATUS_APPROVED_KEPALA);
    }

    /**
     * Scope status changes to approved_ga
     */
    public function scopeToApprovedGA($query)
    {
        return $query->where('to_status', self::STATUS_APPROVED_GA);
    }

    /**
     * Scope status changes to rejected
     */
    public function scopeToRejected($query)
    {
        return $query->where('to_status', self::STATUS_REJECTED);
    }

    /**
     * Scope recent changes
     */
    public function scopeRecentFirst($query)
    {
        return $query->orderBy('changed_at', 'desc');
    }

    /**
     * Scope oldest changes
     */
    public function scopeOldestFirst($query)
    {
        return $query->orderBy('changed_at', 'asc');
    }

    /**
     * Scope by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('changed_at', [$startDate, $endDate]);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get from status label
     */
    public function getFromStatusLabel(): string
    {
        return $this->getStatusLabel($this->from_status);
    }

    /**
     * Get to status label
     */
    public function getToStatusLabel(): string
    {
        return $this->getStatusLabel($this->to_status);
    }

    /**
     * ✅ FIXED: Get status label (sesuai LoanRequest constants)
     */
    private function getStatusLabel(?string $status): string
    {
        if (is_null($status)) {
            return '-';
        }

        $labels = [
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_APPROVED_KEPALA => 'Approved (Kepala/Akuntansi)',
            self::STATUS_APPROVED_GA => 'Approved (GA)',
            self::STATUS_ASSIGNED => 'Vehicle Assigned',
            self::STATUS_IN_USE => 'Sedang Digunakan',
            self::STATUS_RETURNED => 'Dikembalikan',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];

        return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Get changer name
     */
    public function getChangerName(): string
    {
        return $this->changedBy ? $this->changedBy->full_name : 'System';
    }

    /**
     * Get formatted changed date
     */
    public function getFormattedChangedAt(): string
    {
        return $this->changed_at ? $this->changed_at->format('d M Y H:i') : '-';
    }

    /**
     * Get status change summary
     */
    public function getSummary(): string
    {
        $from = $this->getFromStatusLabel();
        $to = $this->getToStatusLabel();
        $changer = $this->getChangerName();
        $date = $this->getFormattedChangedAt();

        return "{$from} → {$to} oleh {$changer} pada {$date}";
    }

    /**
     * Get change note or default
     */
    public function getChangeNote(): string
    {
        return $this->change_note ?? '-';
    }

    /**
     * Check if status changed to approved
     */
    public function isApproval(): bool
    {
        return in_array($this->to_status, [
            self::STATUS_APPROVED_KEPALA,
            self::STATUS_APPROVED_GA,
        ]);
    }

    /**
     * Check if status changed to rejected
     */
    public function isRejection(): bool
    {
        return $this->to_status === self::STATUS_REJECTED;
    }

    /**
     * Check if status changed to assigned
     */
    public function isAssignment(): bool
    {
        return $this->to_status === self::STATUS_ASSIGNED;
    }

    /**
     * Check if status changed to in use
     */
    public function isStartUsage(): bool
    {
        return $this->to_status === self::STATUS_IN_USE;
    }

    /**
     * Check if status changed to returned
     */
    public function isReturn(): bool
    {
        return $this->to_status === self::STATUS_RETURNED;
    }

    /**
     * Get status color for badge
     */
    public function getStatusColor(): string
    {
        return match($this->to_status) {
            self::STATUS_SUBMITTED => 'blue',
            self::STATUS_APPROVED_KEPALA => 'yellow',
            self::STATUS_APPROVED_GA => 'green',
            self::STATUS_ASSIGNED => 'purple',
            self::STATUS_IN_USE => 'indigo',
            self::STATUS_RETURNED => 'gray',
            self::STATUS_REJECTED => 'red',
            self::STATUS_CANCELLED => 'orange',
            default => 'gray',
        };
    }

    /**
     * Get log details array
     */
    public function getDetails(): array
    {
        return [
            'from_status' => $this->getFromStatusLabel(),
            'to_status' => $this->getToStatusLabel(),
            'changed_by' => $this->getChangerName(),
            'changed_at' => $this->getFormattedChangedAt(),
            'change_note' => $this->getChangeNote(),
            'is_approval' => $this->isApproval(),
            'is_rejection' => $this->isRejection(),
        ];
    }

    /**
     * Create a new status log entry
     */
    public static function createLog(
        int $loanRequestId,
        ?string $fromStatus,
        string $toStatus,
        ?int $changedBy = null,
        ?string $note = null
    ): self {
        return self::create([
            'loan_request_id' => $loanRequestId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => $changedBy ?? auth::user()->id,
            'change_note' => $note,
        ]);
    }
}
