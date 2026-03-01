<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



/**
 * @property \Illuminate\Notifications\DatabaseNotificationCollection $notifications
 * @property \Illuminate\Notifications\DatabaseNotificationCollection $unreadNotifications
 */

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'unit_id',
        'full_name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== ACCESSOR & MUTATOR ====================
    
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['full_name'] = $value;
    }

    // ==================== RELATIONSHIPS ====================
    
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function unitAsKepala()
    {
        return $this->hasOne(Unit::class, 'kepala_departemen_id');
    }

    public function loanRequests()
    {
        return $this->hasMany(LoanRequest::class, 'requester_id');
    }

    // Approvals via loan_approvals table
    public function approvals()
    {
        return $this->hasMany(LoanApproval::class, 'approver_id');
    }

    public function assignments()
    {
        return $this->hasMany(LoanAssignment::class, 'assigned_by');
    }

    public function receivedReturns()
    {
        return $this->hasMany(VehicleReturn::class, 'received_by');
    }

    public function statusChanges()
    {
        return $this->hasMany(LoanStatusLog::class, 'changed_by');
    }

    public function uploadedAttachments()
    {
        return $this->hasMany(LoanRequestAttachment::class, 'uploaded_by');
    }

    public function returnExpenses()
    {
        return $this->hasMany(ReturnExpense::class, 'created_by');
    }

    // ==================== SCOPES ====================
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['admin_ga', 'admin_akuntansi']);
    }

    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeKepalaDepartemen($query)
    {
        return $query->where('role', 'kepala_departemen');
    }

    public function scopeAdminGA($query)
    {
        return $query->where('role', 'admin_ga');
    }

    public function scopeAdminAkuntansi($query)
    {
        return $query->where('role', 'admin_akuntansi');
    }

    public function scopeInUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    // ==================== ROLE CHECKING ====================
    
    /**
     * Check if user is Admin GA
     */
    public function isAdminGA(): bool
    {
        return $this->role === 'admin_ga';
    }

    /**
     * Check if user is Admin Akuntansi
     */
    public function isAdminAkuntansi(): bool
    {
        return $this->role === 'admin_akuntansi';
    }

    /**
     * Check if user is Kepala Departemen
     */
    public function isKepalaDepartemen(): bool
    {
        return $this->role === 'kepala_departemen';
    }

    /**
     * Check if user is regular user
     */
    public function isRegularUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Alias
     */
    public function isUser(): bool
    {
        return $this->isRegularUser();
    }

    /**
     * Check if user has any admin role
     */
    public function hasAdminRole(): bool
    {
        return in_array($this->role, ['admin_ga', 'admin_akuntansi']);
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    // ==================== PERMISSION CHECKS ====================
    
    /**
     * FIXED: Hanya Admin GA yang TIDAK bisa meminjam
     */
    public function canBorrowVehicle(): bool
    {
        return $this->role !== 'admin_ga' && $this->is_active;
    }

    /**
     * Can approve loan requests
     */
    public function canApproveLoan(): bool
    {
        return in_array($this->role, ['admin_ga', 'kepala_departemen', 'admin_akuntansi']) 
            && $this->is_active;
    }

    /**
     * Only Admin GA can assign vehicles
     */
    public function canAssignVehicle(): bool
    {
        return $this->role === 'admin_ga' && $this->is_active;
    }

    /**
     * Only Admin GA can manage units
     */
    public function canManageUnits(): bool
    {
        return $this->role === 'admin_ga';
    }

    /**
     * Only Admin GA can manage vehicles
     */
    public function canManageVehicles(): bool
    {
        return $this->role === 'admin_ga';
    }

    /**
     * Only Admin GA can process returns
     */
    public function canProcessReturn(): bool
    {
        return $this->role === 'admin_ga';
    }

    /**
     * Only Admin Akuntansi can monitor returns
     */
    public function canMonitorReturns(): bool
    {
        return $this->role === 'admin_akuntansi';
    }

    /**
     * Only Admin GA can view all history
     */
    public function canViewAllHistory(): bool
    {
        return $this->role === 'admin_ga';
    }

    /**
     * Only Admin Akuntansi can view return expenses
     */
    public function canViewReturnExpenses(): bool
    {
        return $this->role === 'admin_akuntansi';
    }

    /**
     * Check if user can create target role
     */
    public function canCreateRole($targetRole): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Admin GA HANYA bisa create kepala_departemen
        if ($this->isAdminGA()) {
            return $targetRole === 'kepala_departemen';
        }

        // Kepala Departemen & Admin Akuntansi HANYA bisa create user biasa
        if ($this->isKepalaDepartemen() || $this->isAdminAkuntansi()) {
            return $targetRole === 'user';
        }

        return false;
    }

    /**
     * Check if user can manage another user
     */
    public function canManageUser(User $targetUser): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Admin GA HANYA bisa manage kepala_departemen
        if ($this->isAdminGA()) {
            return $targetUser->role === 'kepala_departemen';
        }

        // Kepala Departemen & Admin Akuntansi hanya bisa manage user di unitnya
        if ($this->isKepalaDepartemen() || $this->isAdminAkuntansi()) {
            return $targetUser->unit_id === $this->unit_id 
                && $targetUser->role === 'user';
        }

        return false;
    }

    /**
     * Check if user can view a loan request
     */
    public function canViewLoan(LoanRequest $loan): bool
    {
        // Admin GA bisa lihat semua
        if ($this->isAdminGA()) {
            return true;
        }

        // Admin Akuntansi bisa lihat semua (untuk monitoring return)
        if ($this->isAdminAkuntansi()) {
            return true;
        }

        // Kepala Departemen hanya lihat dari unitnya
        if ($this->isKepalaDepartemen()) {
            return $loan->unit_id === $this->unit_id;
        }

        // User biasa hanya lihat miliknya sendiri
        if ($this->isRegularUser()) {
            return $loan->requester_id === $this->id;
        }

        return false;
    }

    /**
     * Check if user can approve a specific loan request
     */
    public function canApproveLoanRequest(LoanRequest $loan): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Admin GA bisa approve semua (level 2)
        if ($this->isAdminGA()) {
            return true;
        }

        // Kepala Departemen & Admin Akuntansi hanya bisa approve dari unitnya (level 1)
        if ($this->isKepalaDepartemen() || $this->isAdminAkuntansi()) {
            return $loan->unit_id === $this->unit_id;
        }

        return false;
    }

    /**
     * Get approval level for this user
     */
    public function getApprovalLevel(): ?string
    {
        if ($this->isAdminGA()) {
            return 'ga'; // Level 2
        }

        if ($this->isKepalaDepartemen() || $this->isAdminAkuntansi()) {
            return 'kepala'; // Level 1
        }

        return null;
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'admin_ga' => 'Admin GA',
            'admin_akuntansi' => 'Admin Akuntansi',
            'kepala_departemen' => 'Kepala Departemen',
            'user' => 'User',
            default => 'Unknown'
        };
    }

    /**
     * Get dashboard route based on role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
        'admin_ga'          => 'dashboard', // ✅
        'admin_akuntansi'   => 'dashboard', // ✅ sama, tapi view beda
        'kepala_departemen' => 'dashboard', // ✅ sama, tapi view beda
        'user'              => 'dashboard',
            default => 'dashboard'
        };
    }

    // ==================== ADDITIONAL HELPERS ====================

    /**
     * Get unit name
     */
    public function getUnitName(): string
    {
        return $this->unit?->name ?? 'No Unit';
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Get status color
     */
    public function getStatusColor(): string
    {
        return $this->is_active ? 'green' : 'red';
    }

    /**
     * Get pending loan requests count
     */
    public function getPendingLoanRequestsCount(): int
    {
        return $this->loanRequests()
            ->where('status', LoanRequest::STATUS_SUBMITTED)
            ->count();
    }

    /**
     * Get approved loan requests count
     */
    public function getApprovedLoanRequestsCount(): int
    {
        return $this->loanRequests()
            ->whereIn('status', [
                LoanRequest::STATUS_APPROVED_KEPALA,
                LoanRequest::STATUS_APPROVED_GA,
                LoanRequest::STATUS_ASSIGNED,
                LoanRequest::STATUS_IN_USE,
            ])
            ->count();
    }

    /**
     * Get total loan requests count
     */
    public function getTotalLoanRequestsCount(): int
    {
        return $this->loanRequests()->count();
    }

    /**
     * Get total approvals made
     */
    public function getTotalApprovalsCount(): int
    {
        return $this->approvals()->count();
    }

    /**
     * Check if user is kepala of their unit
     */
    public function isKepalaOfUnit(): bool
    {
        if (!$this->unit) {
            return false;
        }

        return $this->unit->kepala_departemen_id === $this->id;
    }

    /**
     * Get user summary
     */
    public function getSummary(): string
    {
        $role = $this->getRoleDisplayName();
        $unit = $this->getUnitName();
        $status = $this->getStatusLabel();
        
        return "{$this->full_name} | {$role} | {$unit} | {$status}";
    }

    /**
     * Get user details array
     */
    public function getDetails(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'role_display' => $this->getRoleDisplayName(),
            'unit_name' => $this->getUnitName(),
            'is_active' => $this->is_active,
            'status' => $this->getStatusLabel(),
            'approval_level' => $this->getApprovalLevel(),
            'is_kepala_of_unit' => $this->isKepalaOfUnit(),
            'pending_loans_count' => $this->getPendingLoanRequestsCount(),
            'approved_loans_count' => $this->getApprovedLoanRequestsCount(),
            'total_loans_count' => $this->getTotalLoanRequestsCount(),
            'total_approvals_count' => $this->getTotalApprovalsCount(),
        ];
    }
}
