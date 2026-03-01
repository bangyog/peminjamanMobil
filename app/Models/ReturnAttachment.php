<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ReturnAttachment extends Model
{
    use HasFactory;

    // ✅ TYPE CONSTANTS (sesuai enum di database)
    const TYPE_REQUEST_ATTACHMENT = 'request_attachment';
    const TYPE_RETURN_PROOF = 'return_proof';
    const TYPE_EXPENSE_RECEIPT = 'expense_receipt';
    const TYPE_OTHER = 'other';

    protected $fillable = [
        'return_id',
        'type',
        'file_name',
        'file_url',
        'mime_type',
        'file_size_bytes',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size_bytes' => 'integer',
        'uploaded_at' => 'datetime',
    ];

    // ✅ Custom timestamp (pakai uploaded_at)
    public $timestamps = false;

    const CREATED_AT = 'uploaded_at';

    // ==================== RELATIONSHIPS ====================
    
    /**
     * Get the vehicle return that owns this attachment
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
     * Get the user who uploaded this attachment
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Alias: uploader relationship
     */
    public function uploader()
    {
        return $this->uploadedBy();
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
     * Scope by uploader
     */
    public function scopeByUploader($query, $userId)
    {
        return $query->where('uploaded_by', $userId);
    }

    /**
     * Scope by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope request attachments only
     */
    public function scopeRequestAttachments($query)
    {
        return $query->where('type', self::TYPE_REQUEST_ATTACHMENT);
    }

    /**
     * Scope return proofs only
     */
    public function scopeReturnProofs($query)
    {
        return $query->where('type', self::TYPE_RETURN_PROOF);
    }

    /**
     * Scope expense receipts only
     */
    public function scopeExpenseReceipts($query)
    {
        return $query->where('type', self::TYPE_EXPENSE_RECEIPT);
    }

    /**
     * Scope images only
     */
    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    /**
     * Scope PDFs only
     */
    public function scopePdfs($query)
    {
        return $query->where('mime_type', 'application/pdf');
    }

    /**
     * Scope by mime type
     */
    public function scopeByMimeType($query, $mimeType)
    {
        return $query->where('mime_type', $mimeType);
    }

    /**
     * Scope recent uploads
     */
    public function scopeRecentFirst($query)
    {
        return $query->orderBy('uploaded_at', 'desc');
    }

    /**
     * Scope oldest uploads
     */
    public function scopeOldestFirst($query)
    {
        return $query->orderBy('uploaded_at', 'asc');
    }

    /**
     * Scope by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('uploaded_at', [$startDate, $endDate]);
    }

    // ==================== HELPER METHODS ====================
    
    /**
     * Get formatted file size
     */
    public function getFileSizeFormatted(): string
    {
        $bytes = $this->file_size_bytes;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }

    /**
     * Check if file is an image
     */
    public function isImage(): bool
    {
        return strpos($this->mime_type, 'image/') === 0;
    }

    /**
     * Check if file is a PDF
     */
    public function isPDF(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Check if file is a document
     */
    public function isDocument(): bool
    {
        $docTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return in_array($this->mime_type, $docTypes);
    }

    /**
     * Check if attachment is request attachment
     */
    public function isRequestAttachment(): bool
    {
        return $this->type === self::TYPE_REQUEST_ATTACHMENT;
    }

    /**
     * Check if attachment is return proof
     */
    public function isReturnProof(): bool
    {
        return $this->type === self::TYPE_RETURN_PROOF;
    }

    /**
     * Check if attachment is expense receipt
     */
    public function isExpenseReceipt(): bool
    {
        return $this->type === self::TYPE_EXPENSE_RECEIPT;
    }

    /**
     * Check if attachment is other type
     */
    public function isOther(): bool
    {
        return $this->type === self::TYPE_OTHER;
    }

    /**
     * Get file extension
     */
    public function getFileExtension(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Get uploader name
     */
    public function getUploaderName(): string
    {
        return $this->uploadedBy ? $this->uploadedBy->full_name : 'Unknown';
    }

    /**
     * Get formatted uploaded date
     */
    public function getFormattedUploadedAt(): string
    {
        return $this->uploaded_at ? $this->uploaded_at->format('d M Y H:i') : '-';
    }

    /**
     * Get type label
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            self::TYPE_REQUEST_ATTACHMENT => 'Request Attachment',
            self::TYPE_RETURN_PROOF => 'Return Proof',
            self::TYPE_EXPENSE_RECEIPT => 'Expense Receipt',
            self::TYPE_OTHER => 'Other',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    /**
     * Get file icon class (for UI)
     */
    public function getFileIconClass(): string
    {
        if ($this->isImage()) {
            return 'fa-file-image';
        } elseif ($this->isPDF()) {
            return 'fa-file-pdf';
        } elseif ($this->isDocument()) {
            return 'fa-file-word';
        }

        return 'fa-file';
    }

    /**
     * Get file type label
     */
    public function getFileTypeLabel(): string
    {
        if ($this->isImage()) {
            return 'Image';
        } elseif ($this->isPDF()) {
            return 'PDF';
        } elseif ($this->isDocument()) {
            return 'Document';
        }

        return 'File';
    }

    /**
     * Get download URL
     */
    public function getDownloadUrl(): string
    {
        // Jika file_url adalah storage path
        if (Storage::disk('public')->exists($this->file_url)) {
            return Storage::url($this->file_url);
        }


        // Jika sudah full URL
        return $this->file_url;
    }

    /**
     * Check if file exists in storage
     */
    public function fileExists(): bool
    {
        return Storage::disk('public')->exists($this->file_url);
    }

    /**
     * Delete file from storage
     */
    public function deleteFile(): bool
    {
        if ($this->fileExists()) {
            return Storage::disk('public')->delete($this->file_url);
        }

        return false;
    }

    /**
     * Get attachment summary
     */
    public function getSummary(): string
    {
        return "{$this->getTypeLabel()}: {$this->file_name} ({$this->getFileSizeFormatted()})";
    }

    /**
     * Get attachment details array
     */
    public function getDetails(): array
    {
        return [
            'type' => $this->getTypeLabel(),
            'file_name' => $this->file_name,
            'file_size' => $this->getFileSizeFormatted(),
            'file_type' => $this->getFileTypeLabel(),
            'mime_type' => $this->mime_type,
            'uploaded_by' => $this->getUploaderName(),
            'uploaded_at' => $this->getFormattedUploadedAt(),
            'download_url' => $this->getDownloadUrl(),
            'is_image' => $this->isImage(),
            'is_pdf' => $this->isPDF(),
        ];
    }

    /**
     * Override delete to also delete file
     */
    public function delete()
    {
        // Delete file from storage
        $this->deleteFile();

        // Delete record from database
        return parent::delete();
    }
}
