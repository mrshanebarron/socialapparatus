<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $full_legal_name
 * @property string|null $known_as
 * @property string $description
 * @property string|null $category
 * @property array<array-key, mixed> $documents
 * @property array<array-key, mixed>|null $links
 * @property string $status
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property string|null $review_notes
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VerificationAuditLog> $auditLogs
 * @property-read int|null $audit_logs_count
 * @property-read \App\Models\User|null $reviewer
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VerificationDocument> $verificationDocuments
 * @property-read int|null $verification_documents_count
 * @method static Builder<static>|VerificationRequest newModelQuery()
 * @method static Builder<static>|VerificationRequest newQuery()
 * @method static Builder<static>|VerificationRequest pending()
 * @method static Builder<static>|VerificationRequest query()
 * @method static Builder<static>|VerificationRequest whereCategory($value)
 * @method static Builder<static>|VerificationRequest whereCreatedAt($value)
 * @method static Builder<static>|VerificationRequest whereDescription($value)
 * @method static Builder<static>|VerificationRequest whereDocuments($value)
 * @method static Builder<static>|VerificationRequest whereFullLegalName($value)
 * @method static Builder<static>|VerificationRequest whereId($value)
 * @method static Builder<static>|VerificationRequest whereKnownAs($value)
 * @method static Builder<static>|VerificationRequest whereLinks($value)
 * @method static Builder<static>|VerificationRequest whereRejectionReason($value)
 * @method static Builder<static>|VerificationRequest whereReviewNotes($value)
 * @method static Builder<static>|VerificationRequest whereReviewedAt($value)
 * @method static Builder<static>|VerificationRequest whereReviewedBy($value)
 * @method static Builder<static>|VerificationRequest whereStatus($value)
 * @method static Builder<static>|VerificationRequest whereType($value)
 * @method static Builder<static>|VerificationRequest whereUpdatedAt($value)
 * @method static Builder<static>|VerificationRequest whereUserId($value)
 * @mixin \Eloquent
 */
class VerificationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'full_legal_name', 'known_as', 'description', 'category',
        'documents', 'links', 'status', 'reviewed_by', 'reviewed_at', 'review_notes', 'rejection_reason',
    ];

    protected $casts = ['documents' => 'array', 'links' => 'array', 'reviewed_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function verificationDocuments(): HasMany
    {
        return $this->hasMany(VerificationDocument::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(VerificationAuditLog::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function approve(User $reviewer, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);

        VerifiedBadge::create([
            'user_id' => $this->user_id,
            'verification_request_id' => $this->id,
            'badge_type' => $this->type === 'identity' ? 'verified' : $this->type,
            'display_name' => $this->known_as ?? $this->full_legal_name,
            'granted_at' => now(),
            'granted_by' => $reviewer->id,
        ]);

        $this->user->update(['is_verified' => true, 'verification_badge_type' => $this->type, 'verified_at' => now()]);
    }

    public function reject(User $reviewer, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }
}
