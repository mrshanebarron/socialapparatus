<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $group_id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $cover_image
 * @property string|null $location
 * @property string|null $location_address
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property bool $is_online
 * @property string|null $online_link
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property string $privacy
 * @property int $going_count
 * @property int $interested_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $recurrence_type
 * @property string|null $recurrence_days
 * @property string|null $recurrence_end_date
 * @property int|null $parent_event_id
 * @property int $requires_ticket
 * @property int|null $capacity
 * @property int $waitlist_enabled
 * @property-read string|null $cover_image_url
 * @property-read \App\Models\Group|null $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventRsvp> $rsvps
 * @property-read int|null $rsvps_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventTicketType> $ticketTypes
 * @property-read int|null $ticket_types_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventWaitlist> $waitlist
 * @property-read int|null $waitlist_count
 * @method static Builder<static>|Event newModelQuery()
 * @method static Builder<static>|Event newQuery()
 * @method static Builder<static>|Event past()
 * @method static Builder<static>|Event query()
 * @method static Builder<static>|Event upcoming()
 * @method static Builder<static>|Event visibleTo(?\App\Models\User $user)
 * @method static Builder<static>|Event whereCapacity($value)
 * @method static Builder<static>|Event whereCoverImage($value)
 * @method static Builder<static>|Event whereCreatedAt($value)
 * @method static Builder<static>|Event whereDescription($value)
 * @method static Builder<static>|Event whereEndsAt($value)
 * @method static Builder<static>|Event whereGoingCount($value)
 * @method static Builder<static>|Event whereGroupId($value)
 * @method static Builder<static>|Event whereId($value)
 * @method static Builder<static>|Event whereInterestedCount($value)
 * @method static Builder<static>|Event whereIsOnline($value)
 * @method static Builder<static>|Event whereLatitude($value)
 * @method static Builder<static>|Event whereLocation($value)
 * @method static Builder<static>|Event whereLocationAddress($value)
 * @method static Builder<static>|Event whereLongitude($value)
 * @method static Builder<static>|Event whereOnlineLink($value)
 * @method static Builder<static>|Event whereParentEventId($value)
 * @method static Builder<static>|Event wherePrivacy($value)
 * @method static Builder<static>|Event whereRecurrenceDays($value)
 * @method static Builder<static>|Event whereRecurrenceEndDate($value)
 * @method static Builder<static>|Event whereRecurrenceType($value)
 * @method static Builder<static>|Event whereRequiresTicket($value)
 * @method static Builder<static>|Event whereSlug($value)
 * @method static Builder<static>|Event whereStartsAt($value)
 * @method static Builder<static>|Event whereTitle($value)
 * @method static Builder<static>|Event whereUpdatedAt($value)
 * @method static Builder<static>|Event whereUserId($value)
 * @method static Builder<static>|Event whereWaitlistEnabled($value)
 * @mixin \Eloquent
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'title',
        'slug',
        'description',
        'cover_image',
        'location',
        'location_address',
        'latitude',
        'longitude',
        'is_online',
        'online_link',
        'starts_at',
        'ends_at',
        'privacy',
        'going_count',
        'interested_count',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_online' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title) . '-' . Str::random(6);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function goingUsers()
    {
        return $this->rsvps()->where('status', 'going')->with('user');
    }

    public function interestedUsers()
    {
        return $this->rsvps()->where('status', 'interested')->with('user');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>', now())->orderBy('starts_at');
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where('starts_at', '<', now())->orderByDesc('starts_at');
    }

    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            $q->where('privacy', 'public');

            if ($user) {
                $q->orWhere('user_id', $user->id)
                  ->orWhere(function ($q2) use ($user) {
                      $q2->where('privacy', 'friends')
                         ->whereHas('user', function ($q3) use ($user) {
                             $q3->whereHas('sentConnections', fn($q4) => $q4->friends()->accepted()->where('target_id', $user->id))
                                ->orWhereHas('receivedConnections', fn($q4) => $q4->friends()->accepted()->where('user_id', $user->id));
                         });
                  });
            }
        });
    }

    public function getRsvpStatus(User $user): ?string
    {
        $rsvp = $this->rsvps()->where('user_id', $user->id)->first();
        return $rsvp?->status;
    }

    public function setRsvp(User $user, string $status): EventRsvp
    {
        $oldRsvp = $this->rsvps()->where('user_id', $user->id)->first();

        if ($oldRsvp) {
            if ($oldRsvp->status === 'going') $this->decrement('going_count');
            if ($oldRsvp->status === 'interested') $this->decrement('interested_count');

            if ($status === 'not_going') {
                $oldRsvp->delete();
                return $oldRsvp;
            }

            $oldRsvp->update(['status' => $status]);
            if ($status === 'going') $this->increment('going_count');
            if ($status === 'interested') $this->increment('interested_count');

            return $oldRsvp;
        }

        if ($status === 'going') $this->increment('going_count');
        if ($status === 'interested') $this->increment('interested_count');

        return $this->rsvps()->create([
            'user_id' => $user->id,
            'status' => $status,
        ]);
    }

    public function isPast(): bool
    {
        return $this->starts_at < now();
    }

    public function isHappening(): bool
    {
        return $this->starts_at <= now() && ($this->ends_at === null || $this->ends_at >= now());
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? Storage::url($this->cover_image) : null;
    }

    // Ticketing relationships
    public function ticketTypes(): HasMany
    {
        return $this->hasMany(EventTicketType::class);
    }

    public function waitlist(): HasMany
    {
        return $this->hasMany(EventWaitlist::class);
    }

    public function isVisibleTo(?User $user): bool
    {
        if ($this->privacy === 'public') return true;
        if (!$user) return false;
        if ($this->user_id === $user->id) return true;
        if ($this->privacy === 'friends') {
            return $this->user->isFriendsWith($user);
        }
        return false;
    }
}
