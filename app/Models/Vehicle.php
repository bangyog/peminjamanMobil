<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // ✅ STATUS CONSTANTS (sesuai enum di database)
    const STATUS_AVAILABLE = 'available';
    const STATUS_IN_USE = 'in_use';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_RETIRED = 'retired';

    protected $fillable = [
        'unit_code',
        'brand',
        'model',
        'plate_no',
        'seat_capacity',
        'status',
        'odometer_km',
        'notes',
    ];

    protected $casts = [
        'seat_capacity' => 'integer',
        'odometer_km' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * ✅ FIXED: Get all loan requests that prefer this vehicle
     */
    public function loanRequests()
    {
        return $this->hasMany(LoanRequest::class, 'preferred_vehicle_id');
    }

    /**
     * ✅ FIXED: Get all assignments for this vehicle
     */
    public function assignments()
    {
        return $this->hasMany(LoanAssignment::class, 'assigned_vehicle_id');
    }

    /**
     * Get current active assignment (if any)
     */
    public function currentAssignment()
    {
        return $this->hasOne(LoanAssignment::class, 'assigned_vehicle_id')
            ->whereHas('loanRequest', function($q) {
                $q->whereIn('status', [
                    LoanRequest::STATUS_ASSIGNED,
                    LoanRequest::STATUS_IN_USE,
                ]);
            })
            ->latest('assigned_at');
    }

    /**
     * Get current active loan request (if in use)
     */
    public function currentLoanRequest()
    {
        return $this->hasOneThrough(
            LoanRequest::class,
            LoanAssignment::class,
            'assigned_vehicle_id', // Foreign key on loan_assignments
            'id',                   // Foreign key on loan_requests
            'id',                   // Local key on vehicles
            'loan_request_id'       // Local key on loan_assignments
        )->whereIn('status', [
            LoanRequest::STATUS_ASSIGNED,
            LoanRequest::STATUS_IN_USE,
        ])->latest();
    }

    // ==================== SCOPES ====================

    /**
     * Scope available vehicles
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope vehicles in use
     */
    public function scopeInUse($query)
    {
        return $query->where('status', self::STATUS_IN_USE);
    }

    /**
     * Scope vehicles in maintenance
     */
    public function scopeMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    /**
     * Scope retired vehicles
     */
    public function scopeRetired($query)
    {
        return $query->where('status', self::STATUS_RETIRED);
    }

    /**
     * Scope active vehicles (not retired)
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_RETIRED);
    }

    /**
     * Scope by brand
     */
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', 'like', "%{$brand}%");
    }

    /**
     * Scope by seat capacity
     */
    public function scopeBySeatCapacity($query, $capacity)
    {
        return $query->where('seat_capacity', '>=', $capacity);
    }

    /**
     * Scope by plate number
     */
    public function scopeByPlateNo($query, $plateNo)
    {
        return $query->where('plate_no', 'like', "%{$plateNo}%");
    }

    /**
     * Scope search vehicles
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('brand', 'like', "%{$search}%")
              ->orWhere('model', 'like', "%{$search}%")
              ->orWhere('plate_no', 'like', "%{$search}%")
              ->orWhere('unit_code', 'like', "%{$search}%");
        });
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if vehicle is available
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Check if vehicle is in use
     */
    public function isInUse(): bool
    {
        return $this->status === self::STATUS_IN_USE;
    }

    /**
     * Check if vehicle is in maintenance
     */
    public function isMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    /**
     * Check if vehicle is retired
     */
    public function isRetired(): bool
    {
        return $this->status === self::STATUS_RETIRED;
    }

    /**
     * Get full vehicle name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} ({$this->plate_no})";
    }

    /**
     * Get full name with unit code
     */
    public function getFullNameWithCode(): string
    {
        $code = $this->unit_code ? "[{$this->unit_code}] " : '';
        return "{$code}{$this->getFullNameAttribute()}";
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_IN_USE => 'In Use',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_RETIRED => 'Retired',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status color for badge
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'green',
            self::STATUS_IN_USE => 'blue',
            self::STATUS_MAINTENANCE => 'yellow',
            self::STATUS_RETIRED => 'red',
            default => 'gray',
        };
    }

    /**
     * Get formatted odometer
     */
    public function getFormattedOdometer(): string
    {
        return number_format($this->odometer_km, 0, ',', '.') . ' km';
    }

    /**
     * Get current user using this vehicle (if any)
     */
    public function getCurrentUser(): ?User
    {
        $assignment = $this->currentAssignment;
        if (!$assignment) {
            return null;
        }

        return $assignment->loanRequest?->requester;
    }

    /**
     * Get current user name
     */
    public function getCurrentUserName(): string
    {
        $user = $this->getCurrentUser();
        return $user ? $user->full_name : '-';
    }

    /**
     * Update vehicle status
     */
    public function updateStatus(string $status): bool
    {
        $this->status = $status;
        return $this->save();
    }

    /**
     * Update odometer
     */
    public function updateOdometer(int $km): bool
    {
        if ($km < $this->odometer_km) {
            return false; // Odometer tidak boleh mundur
        }

        $this->odometer_km = $km;
        return $this->save();
    }

    /**
     * Mark as available
     */
    public function markAsAvailable(): bool
    {
        return $this->updateStatus(self::STATUS_AVAILABLE);
    }

    /**
     * Mark as in use
     */
    public function markAsInUse(): bool
    {
        return $this->updateStatus(self::STATUS_IN_USE);
    }

    /**
     * Mark as maintenance
     */
    public function markAsMaintenance(): bool
    {
        return $this->updateStatus(self::STATUS_MAINTENANCE);
    }

    /**
     * Mark as retired
     */
    public function markAsRetired(): bool
    {
        return $this->updateStatus(self::STATUS_RETIRED);
    }

    /**
     * Get total assignments count
     */
    public function getTotalAssignmentsCount(): int
    {
        return $this->assignments()->count();
    }

    /**
     * Get total distance traveled (from assignments)
     */
    public function getTotalDistanceTraveled(): int
    {
        // Calculate from odometer history
        // This would require a returns table with odometer_km_end
        return 0; // Placeholder
    }

    /**
     * Check if vehicle can be assigned
     */
    public function canBeAssigned(): bool
    {
        return $this->isAvailable() && !$this->isRetired();
    }

    /**
     * Get vehicle summary
     */
    public function getSummary(): string
    {
        $name = $this->getFullNameAttribute();
        $status = $this->getStatusLabel();
        $odometer = $this->getFormattedOdometer();
        
        return "{$name} | {$status} | {$odometer}";
    }

    /**
     * Get vehicle details array
     */
    public function getDetails(): array
    {
        return [
            'id' => $this->id,
            'unit_code' => $this->unit_code,
            'brand' => $this->brand,
            'model' => $this->model,
            'plate_no' => $this->plate_no,
            'full_name' => $this->getFullNameAttribute(),
            'seat_capacity' => $this->seat_capacity,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'status_color' => $this->getStatusColor(),
            'odometer_km' => $this->odometer_km,
            'odometer_formatted' => $this->getFormattedOdometer(),
            'notes' => $this->notes,
            'current_user' => $this->getCurrentUserName(),
            'can_be_assigned' => $this->canBeAssigned(),
            'total_assignments' => $this->getTotalAssignmentsCount(),
        ];
    }
}
