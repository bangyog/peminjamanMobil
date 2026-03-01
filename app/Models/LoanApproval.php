<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApproval extends Model
{
    use HasFactory;

    // ✅ CONSTANTS untuk approval_level
    const LEVEL_KEPALA = 'kepala';      // Kepala Departemen atau Admin Akuntansi
    const LEVEL_GA = 'ga';              // Admin GA (final approval)

    // ✅ CONSTANTS untuk decision
    const DECISION_APPROVED = 'approved';
    const DECISION_REJECTED = 'rejected';

    protected $fillable = [
        'loan_request_id',
        'approver_id',
        'approval_level',
        'decision',
        'reason',
        'decided_at',
        'approver_signature',
    ];

    protected $casts = [
        'decided_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the loan request that owns this approval
     */
    public function loanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
    }

    /**
     * Get the user who made this approval
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for approved records
     */
    public function scopeApproved($query)
    {
        return $query->where('decision', self::DECISION_APPROVED);
    }

    /**
     * Scope for rejected records
     */
    public function scopeRejected($query)
    {
        return $query->where('decision', self::DECISION_REJECTED);
    }

    /**
     * Scope by approval level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('approval_level', $level);
    }

    /**
     * Scope for kepala level approvals
     */
    public function scopeKepalaLevel($query)
    {
        return $query->where('approval_level', self::LEVEL_KEPALA);
    }

    /**
     * Scope for GA level approvals
     */
    public function scopeGALevel($query)
    {
        return $query->where('approval_level', self::LEVEL_GA);
    }

    /**
     * ✅ NEW: Scope by approver
     */
    public function scopeByApprover($query, $approverId)
    {
        return $query->where('approver_id', $approverId);
    }

    /**
     * ✅ NEW: Scope by loan request
     */
    public function scopeByLoanRequest($query, $loanRequestId)
    {
        return $query->where('loan_request_id', $loanRequestId);
    }

    /**
     * ✅ NEW: Scope latest approvals
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('decided_at', 'desc');
    }

    /**
     * ✅ NEW: Scope approvals by specific user in specific unit
     */
    public function scopeByUnit($query, $unitId)
    {
        return $query->whereHas('loanRequest', function($q) use ($unitId) {
            $q->where('unit_id', $unitId);
        });
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if approval is approved
     */
    public function isApproved(): bool
    {
        return $this->decision === self::DECISION_APPROVED;
    }

    /**
     * Check if approval is rejected
     */
    public function isRejected(): bool
    {
        return $this->decision === self::DECISION_REJECTED;
    }

    /**
     * Check if approval is kepala level
     */
    public function isKepalaLevel(): bool
    {
        return $this->approval_level === self::LEVEL_KEPALA;
    }

    /**
     * Check if approval is GA level
     */
    public function isGALevel(): bool
    {
        return $this->approval_level === self::LEVEL_GA;
    }

    /**
     * Check if approval has signature
     */
    public function hasSignature(): bool
    {
        return !is_null($this->approver_signature);
    }

    /**
     * ✅ NEW: Get approver name
     */
    public function getApproverName(): string
    {
        return $this->approver ? $this->approver->full_name : 'Unknown';
    }

    /**
     * ✅ NEW: Get approver role display name
     */
    public function getApproverRoleName(): string
    {
        return $this->approver ? $this->approver->getRoleDisplayName() : 'Unknown';
    }

    /**
     * ✅ NEW: Get decision label
     */
    public function getDecisionLabel(): string
    {
        return match($this->decision) {
            self::DECISION_APPROVED => 'Disetujui',
            self::DECISION_REJECTED => 'Ditolak',
            default => 'Unknown'
        };
    }

    /**
     * ✅ NEW: Get decision color for badge
     */
    public function getDecisionColor(): string
    {
        return match($this->decision) {
            self::DECISION_APPROVED => 'green',
            self::DECISION_REJECTED => 'red',
            default => 'gray'
        };
    }

    /**
     * ✅ NEW: Get approval level label
     */
    public function getApprovalLevelLabel(): string
    {
        return match($this->approval_level) {
            self::LEVEL_KEPALA => 'Kepala Departemen / Admin Akuntansi',
            self::LEVEL_GA => 'Admin GA',
            default => 'Unknown'
        };
    }

    /**
     * ✅ NEW: Get formatted decided at
     */
    public function getFormattedDecidedAt(): string
    {
        return $this->decided_at ? $this->decided_at->format('d M Y H:i') : '-';
    }

    /**
     * ✅ NEW: Check if approver is kepala departemen
     */
    public function isApprovedByKepala(): bool
    {
        return $this->approver && $this->approver->isKepalaDepartemen();
    }

    /**
     * ✅ NEW: Check if approver is admin akuntansi
     */
    public function isApprovedByAkuntansi(): bool
    {
        return $this->approver && $this->approver->isAdminAkuntansi();
    }

    /**
     * ✅ NEW: Check if approver is admin GA
     */
    public function isApprovedByGA(): bool
    {
        return $this->approver && $this->approver->isAdminGA();
    }

    /**
     * ✅ NEW: Get rejection reason or default text
     */
    public function getRejectionReason(): string
    {
        if (!$this->isRejected()) {
            return '-';
        }
        
        return $this->reason ?? 'Tidak ada alasan';
    }

    /**
     * ✅ NEW: Get approval summary text
     */
    public function getSummary(): string
    {
        $action = $this->isApproved() ? 'menyetujui' : 'menolak';
        $level = $this->getApprovalLevelLabel();
        
        return "{$this->getApproverName()} ({$level}) {$action} pada {$this->getFormattedDecidedAt()}";
    }
}
