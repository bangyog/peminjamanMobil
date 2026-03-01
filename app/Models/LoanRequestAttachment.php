<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LoanRequestAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_request_id',
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

    // ✅ Custom timestamp (pakai uploaded_at, bukan created_at/updated_at)
    public $timestamps = false;

    // ✅ Timestamp column yang digunakan
    const CREATED_AT = 'uploaded_at';

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the loan request that owns this attachment
     */
    public function loanRequest()
    {
        return $this->belongsTo(LoanRequest::class);
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
     * ✅ NEW: Scope by loan request
     */
    public function scopeByLoanRequest($query, $loanRequestId)
    {
        return $query->where('loan_request_id', $loanRequestId);
    }

    /**
     * ✅ NEW: Scope by uploader
     */
    public function scopeByUploader($query, $userId)
    {
        return $query->where('uploaded_by', $userId);
    }

    /**
     * ✅ NEW: Scope images only
     */
    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    /**
     * ✅ NEW: Scope PDFs only
     */
    public function scopePdfs($query)
    {
        return $query->where('mime_type', 'application/pdf');
    }

    /**
     * ✅ NEW: Scope by mime type
     */
    public function scopeByMimeType($query, $mimeType)
    {
        return $query->where('mime_type', $mimeType);
    }

    /**
     * ✅ NEW: Scope recent uploads
     */
    public function scopeRecentFirst($query)
    {
        return $query->orderBy('uploaded_at', 'desc');
    }

    /**
     * ✅ NEW: Scope oldest first
     */
    public function scopeOldestFirst($query)
    {
        return $query->orderBy('uploaded_at', 'asc');
    }

    /**
     * ✅ NEW: Scope by date range
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
     * ✅ NEW: Check if file is a document
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
     * ✅ NEW: Get file extension
     */
    public function getFileExtension(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * ✅ NEW: Get uploader name
     */
    public function getUploaderName(): string
    {
        return $this->uploadedBy ? $this->uploadedBy->full_name : 'Unknown';
    }

    /**
     * ✅ NEW: Get formatted uploaded date
     */
    public function getFormattedUploadedAt(): string
    {
        return $this->uploaded_at ? $this->uploaded_at->format('d M Y H:i') : '-';
    }

    /**
     * ✅ NEW: Get file icon class (for UI)
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
     * ✅ NEW: Get file type label
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
     * ✅ NEW: Get download URL
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
     * ✅ NEW: Check if file exists in storage
     */
    public function fileExists(): bool
    {
        return Storage::disk('public')->exists($this->file_url);
    }

    /**
     * ✅ NEW: Delete file from storage
     */
    public function deleteFile(): bool
    {
        if ($this->fileExists()) {
            return Storage::disk('public')->delete($this->file_url);
        }

        return false;
    }

    /**
     * ✅ NEW: Get attachment summary
     */
    public function getSummary(): string
    {
        return "{$this->file_name} ({$this->getFileSizeFormatted()}) - {$this->getFileTypeLabel()}";
    }

    /**
     * ✅ NEW: Get attachment details array
     */
    public function getDetails(): array
    {
        return [
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
     * ✅ NEW: Override delete to also delete file
     */
    public function delete()
    {
        // Delete file from storage
        $this->deleteFile();

        // Delete record from database
        return parent::delete();
    }
}
