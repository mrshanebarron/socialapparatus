<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $content_verification_id
 * @property int $user_id
 * @property string $reason
 * @property array<array-key, mixed>|null $evidence
 * @property string $status
 * @property string|null $review_notes
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ContentVerification $contentVerification
 * @property-read \App\Models\User|null $reviewedBy
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereContentVerificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereEvidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereReviewNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FactCheckDispute whereUserId($value)
 * @mixin \Eloquent
 */
class FactCheckDispute extends Model
{
    protected $fillable = [
        'content_verification_id', 'user_id', 'reason', 'evidence',
        'status', 'review_notes', 'reviewed_by', 'reviewed_at'
    ];

    protected $casts = [
        'evidence' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function contentVerification(): BelongsTo
    {
        return $this->belongsTo(ContentVerification::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
