<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $reporter_id
 * @property string $reportable_type
 * @property int $reportable_id
 * @property string $reason
 * @property string|null $details
 * @property string $status
 * @property int|null $reviewed_by
 * @property string|null $resolution_notes
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $reportable
 * @property-read \App\Models\User $reporter
 * @property-read \App\Models\User|null $reviewer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReportableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReportableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReporterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereResolutionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'details',
        'status',
        'reviewed_by',
        'resolution_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function markAsReviewing(User $reviewer): void
    {
        $this->update([
            'status' => 'reviewing',
            'reviewed_by' => $reviewer->id,
        ]);
    }

    public function resolve(User $reviewer, string $notes = null): void
    {
        $this->update([
            'status' => 'resolved',
            'reviewed_by' => $reviewer->id,
            'resolution_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    public function dismiss(User $reviewer, string $notes = null): void
    {
        $this->update([
            'status' => 'dismissed',
            'reviewed_by' => $reviewer->id,
            'resolution_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public const REASONS = [
        'spam' => 'Spam',
        'harassment' => 'Harassment or Bullying',
        'hate_speech' => 'Hate Speech',
        'violence' => 'Violence or Threats',
        'nudity' => 'Nudity or Sexual Content',
        'false_info' => 'False Information',
        'scam' => 'Scam or Fraud',
        'intellectual_property' => 'Intellectual Property Violation',
        'self_harm' => 'Self-Harm or Suicide',
        'terrorism' => 'Terrorism',
        'other' => 'Other',
    ];
}
