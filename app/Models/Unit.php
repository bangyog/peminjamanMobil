<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kepala_departemen_id',
        'is_trial',
        'trial_expires_at',
        'is_active',
    ];

    protected $casts = [
        'is_trial' => 'boolean',
        'is_active' => 'boolean',
        'trial_expires_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * ✅ FIXED: Get all users in this unit
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all loan requests from this unit
     */
    public function loanRequests()
    {
        return $this->hasMany(LoanRequest::class);
    }

    /**
     * Get the kepala departemen (or admin akuntansi) of this unit
     */
    public function kepalaDepartemen()
    {
        return $this->belongsTo(User::class, 'kepala_departemen_id');
    }

    /**
     * Alias: kepala relationship
     */
    public function kepala()
    {
        return $this->kepalaDepartemen();
    }

    /**
     * Get regular users (non-kepala) in this unit
     */
    public function regularUsers()
    {
        return $this->users()->where('role', 'user');
    }

    /**
     * Get kepala departemen users in this unit
     */
    public function kepalaUsers()
    {
        return $this->users()->where('role', 'kepala_departemen');
    }

    /**
     * Get admin akuntansi in this unit (usually only one or none)
     */
    public function adminAkuntansi()
    {
        return $this->users()->where('role', 'admin_akuntansi')->first();
    }

    // ==================== SCOPES ====================

    /**
     * Scope active units
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope inactive units
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope units that are not expired (trial units)
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('is_trial', false)
              ->orWhere('trial_expires_at', '>', now());
        });
    }

    /**
     * Scope trial units
     */
    public function scopeTrial($query)
    {
        return $query->where('is_trial', true);
    }

    /**
     * Scope non-trial units
     */
    public function scopeNonTrial($query)
    {
        return $query->where('is_trial', false);
    }

    /**
     * Scope units with kepala departemen
     */
    public function scopeWithKepala($query)
    {
        return $query->whereNotNull('kepala_departemen_id');
    }

    /**
     * Scope units without kepala departemen
     */
    public function scopeWithoutKepala($query)
    {
        return $query->whereNull('kepala_departemen_id');
    }

    /**
     * Scope by name
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if unit is GA
     */
    public function isGA(): bool
    {
        return strtolower($this->name) === 'ga';
    }

    /**
     * Check if unit is Akuntansi
     */
    public function isAkuntansi(): bool
    {
        return strtolower($this->name) === 'akuntansi';
    }

    /**
     * Check if unit has kepala departemen assigned
     */
    public function hasKepalaDepartemen(): bool
    {
        return !is_null($this->kepala_departemen_id);
    }

    /**
     * Get kepala departemen name
     */
    public function getKepalaName(): ?string
    {
        return $this->kepalaDepartemen?->full_name;
    }

    /**
     * Get kepala departemen role display name
     */
    public function getKepalaRole(): ?string
    {
        return $this->kepalaDepartemen?->getRoleDisplayName();
    }

    /**
     * Check if unit is trial
     */
    public function isTrial(): bool
    {
        return $this->is_trial;
    }

    /**
     * Check if trial is expired
     */
    public function isTrialExpired(): bool
    {
        if (!$this->is_trial) {
            return false;
        }

        return $this->trial_expires_at && $this->trial_expires_at->isPast();
    }

    /**
     * Check if unit is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get trial expiry date formatted
     */
    public function getFormattedTrialExpiresAt(): ?string
    {
        return $this->trial_expires_at ? $this->trial_expires_at->format('d M Y') : null;
    }

    /**
     * Get days until trial expires
     */
    public function getDaysUntilTrialExpires(): ?int
    {
        if (!$this->is_trial || !$this->trial_expires_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->trial_expires_at, false));
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        if (!$this->is_active) {
            return 'Inactive';
        }

        if ($this->isTrialExpired()) {
            return 'Trial Expired';
        }

        if ($this->is_trial) {
            return 'Trial';
        }

        return 'Active';
    }

    /**
     * Get status color for badge
     */
    public function getStatusColor(): string
    {
        if (!$this->is_active) {
            return 'red';
        }

        if ($this->isTrialExpired()) {
            return 'orange';
        }

        if ($this->is_trial) {
            return 'yellow';
        }

        return 'green';
    }

    /**
     * Count active users in unit
     */
    public function getActiveUsersCount(): int
    {
        return $this->users()->where('is_active', true)->count();
    }

    /**
     * Count regular users in unit
     */
    public function getRegularUsersCount(): int
    {
        return $this->regularUsers()->count();
    }

    /**
     * Count pending loan requests
     */
    public function getPendingLoanRequestsCount(): int
    {
        return $this->loanRequests()
            ->where('status', 'submitted')
            ->count();
    }

    /**
     * Get unit summary
     */
    public function getSummary(): string
    {
        $kepala = $this->hasKepalaDepartemen() ? $this->getKepalaName() : 'Belum ada kepala';
        $status = $this->getStatusLabel();
        $users = $this->getActiveUsersCount();
        
        return "{$this->name} | Kepala: {$kepala} | {$users} users | {$status}";
    }

    /**
     * Get unit details array
     */
    public function getDetails(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'kepala_name' => $this->getKepalaName(),
            'kepala_role' => $this->getKepalaRole(),
            'has_kepala' => $this->hasKepalaDepartemen(),
            'is_active' => $this->is_active,
            'is_trial' => $this->is_trial,
            'is_trial_expired' => $this->isTrialExpired(),
            'trial_expires_at' => $this->getFormattedTrialExpiresAt(),
            'days_until_expires' => $this->getDaysUntilTrialExpires(),
            'status' => $this->getStatusLabel(),
            'active_users_count' => $this->getActiveUsersCount(),
            'regular_users_count' => $this->getRegularUsersCount(),
            'pending_loans_count' => $this->getPendingLoanRequestsCount(),
        ];
    }

    /**
     * Check if user can manage this unit
     */
    public function canBeManagedBy(User $user): bool
    {
        // Admin GA bisa manage semua unit
        if ($user->isAdminGA()) {
            return true;
        }

        // Kepala departemen hanya bisa manage unitnya sendiri (partial access)
        if ($user->isKepalaDepartemen()) {
            return $user->unit_id === $this->id;
        }

        return false;
    }

    /**
     * Activate unit
     */
    public function activate(): bool
    {
        $this->is_active = true;
        return $this->save();
    }

    /**
     * Deactivate unit
     */
    public function deactivate(): bool
    {
        $this->is_active = false;
        return $this->save();
    }
}
