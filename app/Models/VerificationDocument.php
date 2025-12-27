<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $verification_request_id
 * @property string $type
 * @property string $file_path
 * @property string $original_name
 * @property string $mime_type
 * @property int $file_size
 * @property bool $is_verified
 * @property string|null $verification_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $file_url
 * @property-read \App\Models\VerificationRequest $verificationRequest
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereVerificationNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationDocument whereVerificationRequestId($value)
 * @mixin \Eloquent
 */
class VerificationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'verification_request_id', 'type', 'file_path', 'original_name', 'mime_type',
        'file_size', 'is_verified', 'verification_notes',
    ];

    protected $casts = ['is_verified' => 'boolean'];

    public function verificationRequest(): BelongsTo
    {
        return $this->belongsTo(VerificationRequest::class);
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }
}
