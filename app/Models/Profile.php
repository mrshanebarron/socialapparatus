<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $username
 * @property string|null $display_name
 * @property string|null $bio
 * @property string|null $location
 * @property string|null $website
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property string|null $gender
 * @property string|null $avatar
 * @property string|null $cover_photo
 * @property string $profile_visibility
 * @property bool $show_online_status
 * @property bool $show_last_seen
 * @property bool $allow_friend_requests
 * @property bool $allow_messages
 * @property \Illuminate\Support\Carbon|null $last_seen_at
 * @property bool $is_online
 * @property int $friends_count
 * @property int $followers_count
 * @property int $following_count
 * @property int $posts_count
 * @property int $profile_views_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $interests
 * @property bool $onboarding_completed
 * @property bool $allow_questions
 * @property bool $allow_anonymous_questions
 * @property int $accept_tips
 * @property numeric $minimum_tip
 * @property int $accept_gifts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProfileFieldValue> $fieldValues
 * @property-read int|null $field_values_count
 * @property-read string $avatar_url
 * @property-read string|null $cover_photo_url
 * @property-read string $display_name_or_username
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProfileView> $views
 * @property-read int|null $views_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAcceptGifts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAcceptTips($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAllowAnonymousQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAllowFriendRequests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAllowMessages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAllowQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCoverPhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereFollowersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereFollowingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereFriendsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereInterests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereMinimumTip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereOnboardingCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile wherePostsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereProfileViewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereProfileVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereShowLastSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereShowOnlineStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereWebsite($value)
 * @mixin \Eloquent
 */
class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'display_name',
        'bio',
        'headline',
        'location',
        'website',
        'birthday',
        'gender',
        'avatar',
        'cover_photo',
        'interests',
        'onboarding_completed',
        'profile_visibility',
        'show_online_status',
        'show_last_seen',
        'allow_friend_requests',
        'allow_messages',
        'allow_questions',
        'allow_anonymous_questions',
        'last_seen_at',
        'is_online',
    ];

    protected $casts = [
        'birthday' => 'date',
        'show_online_status' => 'boolean',
        'show_last_seen' => 'boolean',
        'allow_friend_requests' => 'boolean',
        'allow_messages' => 'boolean',
        'allow_questions' => 'boolean',
        'allow_anonymous_questions' => 'boolean',
        'is_online' => 'boolean',
        'last_seen_at' => 'datetime',
        'interests' => 'array',
        'onboarding_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(ProfileFieldValue::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(ProfileView::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }

        // Default avatar using UI Avatars
        $name = urlencode($this->display_name ?? $this->user->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=6366f1&color=fff";
    }

    public function getCoverPhotoUrlAttribute(): ?string
    {
        if ($this->cover_photo) {
            return Storage::url($this->cover_photo);
        }
        return null;
    }

    public function getDisplayNameOrUsernameAttribute(): string
    {
        return $this->display_name ?? $this->username ?? $this->user->name;
    }

    public function incrementViewCount(): void
    {
        $this->increment('profile_views_count');
    }

    public function recordView(?User $viewer, ?string $ip = null, ?string $userAgent = null): void
    {
        // Don't record views from the profile owner
        if ($viewer && $viewer->id === $this->user_id) {
            return;
        }

        // Rate limit: only record one view per viewer per hour
        $recentView = $this->views()
            ->where(function ($query) use ($viewer, $ip) {
                if ($viewer) {
                    $query->where('viewer_id', $viewer->id);
                } else {
                    $query->where('ip_address', $ip);
                }
            })
            ->where('created_at', '>', now()->subHour())
            ->exists();

        if (!$recentView) {
            $this->views()->create([
                'viewer_id' => $viewer?->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
            $this->incrementViewCount();
        }
    }

    public function updateOnlineStatus(bool $isOnline): void
    {
        $this->update([
            'is_online' => $isOnline,
            'last_seen_at' => now(),
        ]);
    }

    public function isVisibleTo(?User $viewer): bool
    {
        if ($this->profile_visibility === 'public') {
            return true;
        }

        if (!$viewer) {
            return false;
        }

        if ($viewer->id === $this->user_id) {
            return true;
        }

        if ($this->profile_visibility === 'friends') {
            // TODO: Check if viewer is a friend
            return true; // Placeholder until friends system is built
        }

        return false;
    }

    public function getCustomFieldValue(string $fieldName): ?string
    {
        $fieldValue = $this->fieldValues()
            ->whereHas('field', fn($q) => $q->where('name', $fieldName))
            ->first();

        return $fieldValue?->value;
    }

    public function setCustomFieldValue(ProfileField $field, ?string $value, ?string $visibility = null): void
    {
        $this->fieldValues()->updateOrCreate(
            ['profile_field_id' => $field->id],
            [
                'value' => $value,
                'visibility' => $visibility,
            ]
        );
    }
}
