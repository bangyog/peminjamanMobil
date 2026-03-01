<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ReturnExpense extends Model
{
    use HasFactory;

    // ✅ TYPE CONSTANTS (sesuai enum di database)
    const TYPE_FUEL = 'fuel';
    const TYPE_TOLL = 'toll';
    const TYPE_PARKING = 'parking';
    const TYPE_REPAIR = 'repair';
    const TYPE_OTHER = 'other';

    protected $fillable = [
        'return_id',
        'type',
        'description',
        'amount',
        'currency',
        'receipt_url',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    // ✅ Custom timestamp (hanya created_at)
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    // ==================== RELATIONSHIPS ====================
    
    /**
     * Get the vehicle return that owns this expense
     */
    public function vehicleReturn()
    {
        return $this->belongsTo(VehicleReturn::class, 'return_id');
    }

    /**
     * Alias: return relationship
     */
    public function return()
    {
        return $this->vehicleReturn();
    }

    /**
     * Get the user who created this expense
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Alias: creator relationship
     */
    public function creator()
    {
        return $this->createdBy();
    }

    // ==================== SCOPES ====================

    /**
     * Scope by vehicle return
     */
    public function scopeByReturn($query, $returnId)
    {
        return $query->where('return_id', $returnId);
    }

    /**
     * Scope by creator
     */
    public function scopeByCreator($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Scope by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope fuel expenses
     */
    public function scopeFuel($query)
    {
        return $query->where('type', self::TYPE_FUEL);
    }

    /**
     * Scope toll expenses
     */
    public function scopeToll($query)
    {
        return $query->where('type', self::TYPE_TOLL);
    }

    /**
     * Scope parking expenses
     */
    public function scopeParking($query)
    {
        return $query->where('type', self::TYPE_PARKING);
    }

    /**
     * Scope repair expenses
     */
    public function scopeRepair($query)
    {
        return $query->where('type', self::TYPE_REPAIR);
    }

    /**
     * Scope other expenses
     */
    public function scopeOther($query)
    {
        return $query->where('type', self::TYPE_OTHER);
    }

    /**
     * Scope expenses with receipt
     */
    public function scopeWithReceipt($query)
    {
        return $query->whereNotNull('receipt_url');
    }

    /**
     * Scope expenses without receipt
     */
    public function scopeWithoutReceipt($query)
    {
        return $query->whereNull('receipt_url');
    }

    /**
     * Scope by currency
     */
    public function scopeByCurrency($query, $currency)
    {
        return $query->where('currency', $currency);
    }

    /**
     * Scope expenses above amount
     */
    public function scopeAboveAmount($query, $amount)
    {
        return $query->where('amount', '>', $amount);
    }

    /**
     * Scope expenses below amount
     */
    public function scopeBelowAmount($query, $amount)
    {
        return $query->where('amount', '<', $amount);
    }

    /**
     * Scope recent expenses
     */
    public function scopeRecentFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope oldest expenses
     */
    public function scopeOldestFirst($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Scope by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // ==================== HELPER METHODS ====================
    
    /**
     * Check if expense has receipt
     */
    public function hasReceipt(): bool
    {
        return !is_null($this->receipt_url) && trim($this->receipt_url) !== '';
    }

    /**
     * Get formatted amount (Indonesian format)
     */
    public function getFormattedAmount(): string
    {
        return number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountWithCurrency(): string
    {
        $currency = $this->currency ?? 'IDR';
        return "{$currency} {$this->getFormattedAmount()}";
    }

    /**
     * Get type label (Indonesian)
     */
    public function getTypeLabel(): string
    {
        $labels = [
            self::TYPE_FUEL => 'Bahan Bakar',
            self::TYPE_TOLL => 'Tol',
            self::TYPE_PARKING => 'Parkir',
            self::TYPE_REPAIR => 'Perbaikan',
            self::TYPE_OTHER => 'Lain-lain',
        ];
        
        return $labels[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Check if expense is fuel
     */
    public function isFuel(): bool
    {
        return $this->type === self::TYPE_FUEL;
    }

    /**
     * Check if expense is toll
     */
    public function isToll(): bool
    {
        return $this->type === self::TYPE_TOLL;
    }

    /**
     * Check if expense is parking
     */
    public function isParking(): bool
    {
        return $this->type === self::TYPE_PARKING;
    }

    /**
     * Check if expense is repair
     */
    public function isRepair(): bool
    {
        return $this->type === self::TYPE_REPAIR;
    }

    /**
     * Check if expense is other
     */
    public function isOther(): bool
    {
        return $this->type === self::TYPE_OTHER;
    }

    /**
     * Get creator name
     */
    public function getCreatorName(): string
    {
        return $this->createdBy ? $this->createdBy->full_name : 'Unknown';
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedAt(): string
    {
        return $this->created_at ? $this->created_at->format('d M Y H:i') : '-';
    }

    /**
     * Get description or default
     */
    public function getDescription(): string
    {
        return $this->description ?? '-';
    }

    /**
     * Get receipt download URL
     */
    public function getReceiptUrl(): ?string
    {
        if (!$this->hasReceipt()) {
            return null;
        }

        // Jika receipt_url adalah storage path
        if (Storage::disk('public')->exists($this->file_url)) {
            return Storage::url($this->file_url);
        }

        // Jika sudah full URL
        return $this->receipt_url;
    }

    /**
     * Check if receipt file exists in storage
     */
    public function receiptExists(): bool
    {
        if (!$this->hasReceipt()) {
            return false;
        }

        return Storage::disk('public')->exists($this->receipt_url);
    }

    /**
     * Delete receipt file from storage
     */
    public function deleteReceipt(): bool
    {
        if ($this->receiptExists()) {
            return Storage::disk('public')->delete($this->receipt_url);
        }

        return false;
    }

    /**
     * Get type color for badge
     */
    public function getTypeColor(): string
    {
        return match($this->type) {
            self::TYPE_FUEL => 'blue',
            self::TYPE_TOLL => 'yellow',
            self::TYPE_PARKING => 'green',
            self::TYPE_REPAIR => 'red',
            self::TYPE_OTHER => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get expense summary
     */
    public function getSummary(): string
    {
        $type = $this->getTypeLabel();
        $amount = $this->getFormattedAmountWithCurrency();
        $desc = $this->description ? " - {$this->description}" : '';
        
        return "{$type}: {$amount}{$desc}";
    }

    /**
     * Get expense details array
     */
    public function getDetails(): array
    {
        return [
            'type' => $this->getTypeLabel(),
            'description' => $this->getDescription(),
            'amount' => $this->amount,
            'amount_formatted' => $this->getFormattedAmountWithCurrency(),
            'currency' => $this->currency ?? 'IDR',
            'has_receipt' => $this->hasReceipt(),
            'receipt_url' => $this->getReceiptUrl(),
            'created_by' => $this->getCreatorName(),
            'created_at' => $this->getFormattedCreatedAt(),
        ];
    }

    /**
     * Override delete to also delete receipt file
     */
    public function delete()
    {
        // Delete receipt file from storage
        $this->deleteReceipt();

        // Delete record from database
        return parent::delete();
    }

    /**
     * Get total expenses for a return
     */
    public static function getTotalByReturn(int $returnId): float
    {
        return self::byReturn($returnId)->sum('amount');
    }

    /**
     * Get total expenses by type for a return
     */
    public static function getTotalByReturnAndType(int $returnId, string $type): float
    {
        return self::byReturn($returnId)->byType($type)->sum('amount');
    }
}
