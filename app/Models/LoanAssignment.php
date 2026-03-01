<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_request_id',
        'assigned_vehicle_id',
        'assigned_by',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the loan request for this assignment
     */
    public function loanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
    }

    /**
     * Get the vehicle assigned to this loan request
     */
    public function assignedVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'assigned_vehicle_id');
    }

    /**
     * Alias: vehicle relationship
     */
    public function vehicle()
    {
        return $this->assignedVehicle();
    }

    /**
     * Get the admin (GA) who assigned the vehicle
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Alias: assigner relationship
     */
    public function assigner()
    {
        return $this->assignedBy();
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
     * Scope by vehicle
     */
    public function scopeByVehicle($query, $vehicleId)
    {
        return $query->where('assigned_vehicle_id', $vehicleId);
    }

    /**
     * Scope by assigner
     */
    public function scopeByAssigner($query, $userId)
    {
        return $query->where('assigned_by', $userId);
    }

    /**
     * Scope recent assignments
     */
    public function scopeRecentFirst($query)
    {
        return $query->orderBy('assigned_at', 'desc');
    }

    /**
     * Scope by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('assigned_at', [$startDate, $endDate]);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get vehicle name
     */
    public function getVehicleName(): string
    {
        if (!$this->assignedVehicle) {
            return 'Vehicle not assigned';
        }

        return "{$this->assignedVehicle->brand} {$this->assignedVehicle->model} ({$this->assignedVehicle->plate_no})";
    }

    /**
     * Get assigner name
     */
    public function getAssignerName(): string
    {
        return $this->assignedBy ? $this->assignedBy->full_name : 'Unknown';
    }

    /**
     * Get formatted assigned date
     */
    public function getFormattedAssignedAt(): string
    {
        return $this->assigned_at ? $this->assigned_at->format('d M Y H:i') : '-';
    }

    /**
     * Get assignment summary
     */
    public function getSummary(): string
    {
        $vehicle = $this->getVehicleName();
        $date = $this->getFormattedAssignedAt();
        
        return "{$vehicle} | Assigned: {$date}";
    }

    /**
     * Get assignment details array
     */
    public function getDetails(): array
    {
        return [
            'vehicle' => $this->getVehicleName(),
            'assigned_by' => $this->getAssignerName(),
            'assigned_at' => $this->getFormattedAssignedAt(),
        ];
    }

    /**
     * Check if assignment is active (loan in use)
     */
    public function isActive(): bool
    {
        if (!$this->loanRequest) {
            return false;
        }

        return in_array($this->loanRequest->status, [
            LoanRequest::STATUS_ASSIGNED,
            LoanRequest::STATUS_IN_USE,
        ]);
    }

    /**
     * Check if assignment is completed (returned)
     */
    public function isCompleted(): bool
    {
        if (!$this->loanRequest) {
            return false;
        }

        return $this->loanRequest->status === LoanRequest::STATUS_RETURNED;
    }

    /**
     * Get vehicle details
     */
    public function getVehicleDetails(): ?array
    {
        if (!$this->assignedVehicle) {
            return null;
        }

        return [
            'id' => $this->assignedVehicle->id,
            'brand' => $this->assignedVehicle->brand,
            'model' => $this->assignedVehicle->model,
            'plate_no' => $this->assignedVehicle->plate_no,
            'full_name' => $this->getVehicleName(),
        ];
    }

    /**
     * Get assignment status label
     */
    public function getStatusLabel(): string
    {
        if ($this->isCompleted()) {
            return 'Completed';
        } elseif ($this->isActive()) {
            return 'Active';
        }

        return 'Unknown';
    }

    /**
     * Get assignment status color
     */
    public function getStatusColor(): string
    {
        if ($this->isCompleted()) {
            return 'gray';
        } elseif ($this->isActive()) {
            return 'green';
        }

        return 'gray';
    }
}
