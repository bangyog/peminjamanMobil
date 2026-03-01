<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    use HasFactory;

    // ✅ STATUS CONSTANTS (Updated sesuai DB)
    const STATUS_SUBMITTED = 'submitted';           // User baru submit
    const STATUS_APPROVED_KEPALA = 'approved_kepala'; // Kepala/Akuntansi sudah approve
    const STATUS_APPROVED_GA = 'approved_ga';       // GA sudah approve (FINAL)
    const STATUS_ASSIGNED = 'assigned';             // GA sudah assign vehicle
    const STATUS_IN_USE = 'in_use';                 // Sedang digunakan
    const STATUS_RETURNED = 'returned';             // Sudah dikembalikan
    const STATUS_REJECTED = 'rejected';             // Ditolak

    protected $fillable = [
        'requester_id',
        'unit_id',
        'preferred_vehicle_id',
        'request_city',
        'purpose',
        'projek',
        'destination',
        'anggaran_awal',      
        'siap_di',
        'kembali_di',
        'requested_vehicle_text',
        'notes',
        'requester_signature',
        'kepala_signature',
        'approver_signature',
        'depart_at',
        'expected_return_at',
        'status',
    ];

    protected $casts = [
        'depart_at' => 'datetime',
        'expected_return_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================
    
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'preferred_vehicle_id');
    }

    public function attachments()
    {
        return $this->hasMany(LoanRequestAttachment::class, 'loan_request_id');
    }

    /**
     * ✅ Approval records dari loan_approvals table
     */
    public function approvals()
    {
        return $this->hasMany(LoanApproval::class, 'loan_request_id');
    }

    /**
     * ✅ Get kepala/akuntansi approval (level 1)
     */
    public function kepalaApproval()
    {
        return $this->hasOne(LoanApproval::class, 'loan_request_id')
                    ->where('approval_level', 'kepala');
    }

    /**
     * ✅ Get GA approval (level 2)
     */
    public function gaApproval()
    {
        return $this->hasOne(LoanApproval::class, 'loan_request_id')
                    ->where('approval_level', 'ga');
    }

    /**
     * ✅ Get rejection record
     */
    public function rejection()
    {
        return $this->hasOne(LoanApproval::class, 'loan_request_id')
                    ->where('decision', 'rejected')
                    ->latest();
    }

    public function assignment()
    {
        return $this->hasOne(LoanAssignment::class, 'loan_request_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(LoanStatusLog::class, 'loan_request_id');
    }

    public function return()
    {
        return $this->hasOne(VehicleReturn::class, 'loan_request_id');
    }

    // ==================== SCOPES ====================
    
    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    public function scopeApprovedKepala($query)
    {
        return $query->where('status', self::STATUS_APPROVED_KEPALA);
    }

    public function scopeApprovedGA($query)
    {
        return $query->where('status', self::STATUS_APPROVED_GA);
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', self::STATUS_ASSIGNED);
    }

    public function scopeInUse($query)
    {
        return $query->where('status', self::STATUS_IN_USE);
    }

    public function scopeReturned($query)
    {
        return $query->where('status', self::STATUS_RETURNED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeByRequester($query, $requesterId)
    {
        return $query->where('requester_id', $requesterId);
    }

    /**
     * ✅ Scope: Pending approval untuk unit tertentu (kepala/akuntansi)
     */
    public function scopePendingForUnit($query, $unitId)
    {
        return $query->where('status', self::STATUS_SUBMITTED)
                     ->where('unit_id', $unitId);
    }

    /**
     * ✅ Scope: Pending approval GA (sudah approved kepala)
     */
    public function scopePendingForGA($query)
    {
        return $query->where('status', self::STATUS_APPROVED_KEPALA);
    }

    // ==================== STATUS CHECKERS ====================
    
    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isApprovedKepala(): bool
    {
        return $this->status === self::STATUS_APPROVED_KEPALA;
    }

    public function isApprovedGA(): bool
    {
        return $this->status === self::STATUS_APPROVED_GA;
    }

    public function isAssigned(): bool
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    public function isInUse(): bool
    {
        return $this->status === self::STATUS_IN_USE;
    }

    public function isReturned(): bool
    {
        return $this->status === self::STATUS_RETURNED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    // ==================== APPROVAL CHECKERS ====================
    
    /**
     * ✅ Menunggu approval kepala/akuntansi
     */
    public function isWaitingKepalaApproval(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    /**
     * ✅ Menunggu approval GA
     */
    public function isWaitingGAApproval(): bool
    {
        return $this->status === self::STATUS_APPROVED_KEPALA;
    }

    /**
     * ✅ Bisa di-approve oleh kepala/akuntansi?
     */
    public function canBeApprovedByKepala(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    /**
     * ✅ Bisa di-approve oleh GA?
     */
    public function canBeApprovedByGA(): bool
    {
        return $this->status === self::STATUS_APPROVED_KEPALA;
    }

    /**
     * ✅ Bisa di-edit?
     */
    public function canBeEdited(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    /**
     * ✅ Bisa di-delete?
     */
    public function canBeDeleted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    /**
     * ✅ Bisa di-assign vehicle?
     */
    public function canBeAssigned(): bool
    {
        return $this->status === self::STATUS_APPROVED_GA;
    }

    /**
     * ✅ Bisa di-return?
     */
    public function canBeReturned(): bool
    {
        return $this->status === self::STATUS_IN_USE;
    }

    // ==================== HELPER METHODS ====================
    
    public function getStatusLabel(): string
    {
        $labels = [
            self::STATUS_SUBMITTED => 'Menunggu Approval Kepala/Akuntansi',
            self::STATUS_APPROVED_KEPALA => 'Menunggu Approval GA',
            self::STATUS_APPROVED_GA => 'Disetujui - Menunggu Assignment',
            self::STATUS_ASSIGNED => 'Vehicle Assigned',
            self::STATUS_IN_USE => 'Sedang Digunakan',
            self::STATUS_RETURNED => 'Sudah Dikembalikan',
            self::STATUS_REJECTED => 'Ditolak',
        ];
        
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColor(): string
    {
        $colors = [
            self::STATUS_SUBMITTED => 'blue',
            self::STATUS_APPROVED_KEPALA => 'yellow',
            self::STATUS_APPROVED_GA => 'green',
            self::STATUS_ASSIGNED => 'purple',
            self::STATUS_IN_USE => 'indigo',
            self::STATUS_RETURNED => 'gray',
            self::STATUS_REJECTED => 'red',
        ];
        
        return $colors[$this->status] ?? 'gray';
    }
}
