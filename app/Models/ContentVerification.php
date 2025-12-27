<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $verifiable_type
 * @property int $verifiable_id
 * @property int $fact_check_label_id
 * @property int|null $verified_by
 * @property string $explanation
 * @property array<array-key, mixed>|null $sources
 * @property string $status
 * @property-read int|null $disputes_count
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FactCheckDispute> $disputes
 * @property-read \App\Models\FactCheckLabel $label
 * @property-read Model|\Eloquent $verifiable
 * @property-read \App\Models\User|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereDisputesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereFactCheckLabelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereSources($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereVerifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereVerifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentVerification whereVerifiedBy($value)
 * @mixin \Eloquent
 */
class ContentVerification extends Model
{
    protected $fillable = [
        'verifiable_type', 'verifiable_id', 'fact_check_label_id', 'verified_by',
        'explanation', 'sources', 'status', 'disputes_count', 'verified_at'
    ];

    protected $casts = [
        'sources' => 'array',
        'verified_at' => 'datetime',
    ];

    public function verifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function label(): BelongsTo
    {
        return $this->belongsTo(FactCheckLabel::class, 'fact_check_label_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(FactCheckDispute::class);
    }
}
