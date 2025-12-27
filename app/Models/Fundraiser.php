<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $story
 * @property string|null $cover_image
 * @property string $category
 * @property numeric $goal_amount
 * @property numeric $raised_amount
 * @property string $currency
 * @property string|null $beneficiary_name
 * @property string $status
 * @property int $donors_count
 * @property int $shares_count
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FundraiserDonation> $donations
 * @property-read int|null $donations_count
 * @property-read string|null $cover_image_url
 * @property-read string $formatted_goal
 * @property-read string $formatted_raised
 * @property-read float $progress_percent
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereBeneficiaryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereDonorsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereGoalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereRaisedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereSharesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereStory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fundraiser withoutTrashed()
 * @mixin \Eloquent
 */
class Fundraiser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'story',
        'cover_image',
        'category',
        'goal_amount',
        'raised_amount',
        'currency',
        'beneficiary_name',
        'status',
        'donors_count',
        'shares_count',
        'ends_at',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'ends_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(FundraiserDonation::class);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->cover_image) {
            return Storage::url($this->cover_image);
        }
        return null;
    }

    public function getProgressPercentAttribute(): float
    {
        if ($this->goal_amount <= 0) return 0;
        return min(100, ($this->raised_amount / $this->goal_amount) * 100);
    }

    public function getFormattedGoalAttribute(): string
    {
        return '$' . number_format($this->goal_amount, 0);
    }

    public function getFormattedRaisedAttribute(): string
    {
        return '$' . number_format($this->raised_amount, 0);
    }

    public function addDonation(FundraiserDonation $donation): void
    {
        if ($donation->status === 'completed') {
            $this->increment('raised_amount', $donation->amount);
            $this->increment('donors_count');

            // Check if goal reached
            if ($this->raised_amount >= $this->goal_amount && $this->status === 'active') {
                $this->update(['status' => 'completed']);
            }
        }
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasEnded(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public const CATEGORIES = [
        'medical' => 'Medical & Health',
        'emergency' => 'Emergency',
        'education' => 'Education',
        'memorial' => 'Memorial & Funeral',
        'nonprofit' => 'Nonprofit & Charity',
        'community' => 'Community',
        'animals' => 'Animals & Pets',
        'environment' => 'Environment',
        'faith' => 'Faith & Religion',
        'sports' => 'Sports & Teams',
        'travel' => 'Travel & Adventure',
        'creative' => 'Creative Projects',
        'business' => 'Business & Startup',
        'other' => 'Other',
    ];
}
