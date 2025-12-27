<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $group_id
 * @property string|null $body
 * @property string $type
 * @property array<array-key, mixed>|null $media
 * @property string|null $link_url
 * @property array<array-key, mixed>|null $link_preview
 * @property string $visibility
 * @property-read int|null $likes_count
 * @property-read int|null $comments_count
 * @property-read int|null $shares_count
 * @property int|null $shared_post_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read int|null $reactions_count
 * @property string|null $location_name
 * @property numeric|null $location_lat
 * @property numeric|null $location_lng
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $edited_at
 * @property bool $is_edited
 * @property string|null $feeling
 * @property string|null $activity
 * @property string|null $activity_detail
 * @property string|null $background_color
 * @property string|null $background_gradient
 * @property string|null $gif_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostEdit> $edits
 * @property-read int|null $edits_count
 * @property-read string|null $activity_display
 * @property-read string|null $background_style
 * @property-read string|null $feeling_display
 * @property-read string $formatted_body
 * @property-read \App\Models\Group|null $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hashtag> $hashtags
 * @property-read int|null $hashtags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $hiddenBy
 * @property-read int|null $hidden_by_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Like> $likes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mention> $mentions
 * @property-read int|null $mentions_count
 * @property-read \App\Models\Page|null $page
 * @property-read \App\Models\Poll|null $poll
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reaction> $reactions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SavedPost> $savedBy
 * @property-read int|null $saved_by_count
 * @property-read Post|null $sharedPost
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Post> $shares
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $topLevelComments
 * @property-read int|null $top_level_comments_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post draft()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post feed(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post scheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post visibleTo(?\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereActivityDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereBackgroundGradient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCommentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereEditedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereFeeling($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereGifUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereIsEdited($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereLinkPreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereLocationLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereLocationLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereLocationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereReactionsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereSharedPostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereSharesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withoutTrashed()
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'group_id',
        'page_id',
        'body',
        'type',
        'media',
        'link_url',
        'link_preview',
        'visibility',
        'shared_post_id',
        'location_name',
        'location_lat',
        'location_lng',
        'scheduled_at',
        'status',
        'edited_at',
        'is_edited',
        'feeling',
        'activity',
        'activity_detail',
        'background_color',
        'background_gradient',
        'gif_url',
    ];

    protected $casts = [
        'media' => 'array',
        'link_preview' => 'array',
        'scheduled_at' => 'datetime',
        'edited_at' => 'datetime',
        'is_edited' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function topLevelComments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function mentions(): MorphMany
    {
        return $this->morphMany(Mention::class, 'mentionable');
    }

    public function getFormattedBodyAttribute(): string
    {
        $body = Mention::renderMentions($this->body ?? '');
        return Hashtag::renderHashtags($body);
    }

    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class)->withTimestamps();
    }

    public function hiddenBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'hidden_posts')->withTimestamps();
    }

    public function isHiddenBy(User $user): bool
    {
        return HiddenPost::isHidden($user, $this);
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    // Feeling/Activity display
    public function getFeelingDisplayAttribute(): ?string
    {
        if (!$this->feeling) return null;
        return self::FEELINGS[$this->feeling] ?? $this->feeling;
    }

    public function getActivityDisplayAttribute(): ?string
    {
        if (!$this->activity) return null;
        $activityName = self::ACTIVITIES[$this->activity] ?? $this->activity;
        return $this->activity_detail ? "$activityName {$this->activity_detail}" : $activityName;
    }

    public function hasBackgroundStyle(): bool
    {
        return !empty($this->background_color) || !empty($this->background_gradient);
    }

    public function getBackgroundStyleAttribute(): ?string
    {
        if ($this->background_gradient) {
            return "background: {$this->background_gradient};";
        }
        if ($this->background_color) {
            return "background-color: {$this->background_color};";
        }
        return null;
    }

    public const FEELINGS = [
        'happy' => 'happy',
        'loved' => 'loved',
        'excited' => 'excited',
        'crazy' => 'crazy',
        'thankful' => 'thankful',
        'blessed' => 'blessed',
        'sad' => 'sad',
        'angry' => 'angry',
        'annoyed' => 'annoyed',
        'confused' => 'confused',
        'tired' => 'tired',
        'motivated' => 'motivated',
        'silly' => 'silly',
        'cool' => 'cool',
        'relaxed' => 'relaxed',
    ];

    public const FEELING_EMOJIS = [
        'happy' => '😊',
        'loved' => '🥰',
        'excited' => '🤩',
        'crazy' => '🤪',
        'thankful' => '🙏',
        'blessed' => '😇',
        'sad' => '😢',
        'angry' => '😠',
        'annoyed' => '😤',
        'confused' => '😕',
        'tired' => '😴',
        'motivated' => '💪',
        'silly' => '😜',
        'cool' => '😎',
        'relaxed' => '😌',
    ];

    public const ACTIVITIES = [
        'celebrating' => 'Celebrating',
        'watching' => 'Watching',
        'eating' => 'Eating',
        'drinking' => 'Drinking',
        'traveling' => 'Traveling to',
        'reading' => 'Reading',
        'playing' => 'Playing',
        'listening' => 'Listening to',
        'looking_for' => 'Looking for',
        'thinking_about' => 'Thinking about',
        'attending' => 'Attending',
        'supporting' => 'Supporting',
    ];

    public const BACKGROUND_COLORS = [
        'bg-gradient-to-r from-indigo-500 to-purple-500' => 'linear-gradient(to right, #6366f1, #a855f7)',
        'bg-gradient-to-r from-pink-500 to-rose-500' => 'linear-gradient(to right, #ec4899, #f43f5e)',
        'bg-gradient-to-r from-green-400 to-cyan-500' => 'linear-gradient(to right, #4ade80, #06b6d4)',
        'bg-gradient-to-r from-orange-400 to-red-500' => 'linear-gradient(to right, #fb923c, #ef4444)',
        'bg-gradient-to-r from-blue-500 to-indigo-500' => 'linear-gradient(to right, #3b82f6, #6366f1)',
        'bg-gradient-to-r from-yellow-400 to-orange-500' => 'linear-gradient(to right, #facc15, #f97316)',
        'bg-gray-800' => '#1f2937',
        'bg-indigo-600' => '#4f46e5',
        'bg-pink-500' => '#ec4899',
        'bg-green-500' => '#22c55e',
    ];

    public function poll(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Poll::class);
    }

    public function savedBy(): HasMany
    {
        return $this->hasMany(SavedPost::class);
    }

    public function edits(): HasMany
    {
        return $this->hasMany(PostEdit::class)->latest();
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function sharedPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'shared_post_id');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(Post::class, 'shared_post_id');
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function like(User $user): void
    {
        if (!$this->isLikedBy($user)) {
            $this->likes()->create(['user_id' => $user->id]);
            $this->increment('likes_count');
        }
    }

    public function unlike(User $user): void
    {
        if ($this->isLikedBy($user)) {
            $this->likes()->where('user_id', $user->id)->delete();
            $this->decrement('likes_count');
        }
    }

    public function toggleLike(User $user): void
    {
        if ($this->isLikedBy($user)) {
            $this->unlike($user);
        } else {
            $this->like($user);
        }
    }

    // Reaction methods
    public function hasReactionFrom(User $user): bool
    {
        return $this->reactions()->where('user_id', $user->id)->exists();
    }

    public function getUserReaction(User $user): ?Reaction
    {
        return $this->reactions()->where('user_id', $user->id)->first();
    }

    public function addReaction(User $user, string $type): Reaction
    {
        $existingReaction = $this->getUserReaction($user);

        if ($existingReaction) {
            if ($existingReaction->type === $type) {
                // Same reaction, remove it
                $existingReaction->delete();
                $this->decrement('reactions_count');
                return $existingReaction;
            } else {
                // Different reaction, update it
                $existingReaction->update(['type' => $type]);
                return $existingReaction;
            }
        }

        // New reaction
        $this->increment('reactions_count');
        return $this->reactions()->create([
            'user_id' => $user->id,
            'type' => $type,
        ]);
    }

    public function removeReaction(User $user): void
    {
        if ($this->hasReactionFrom($user)) {
            $this->reactions()->where('user_id', $user->id)->delete();
            $this->decrement('reactions_count');
        }
    }

    public function getReactionCounts(): array
    {
        return $this->reactions()
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    // Share methods
    public function shareBy(User $user, ?string $body = null, string $visibility = 'public'): Post
    {
        $sharedPost = Post::create([
            'user_id' => $user->id,
            'shared_post_id' => $this->id,
            'body' => $body,
            'type' => 'share',
            'visibility' => $visibility,
        ]);

        $this->increment('shares_count');
        $user->profile?->increment('posts_count');

        return $sharedPost;
    }

    public function canViewBy(?User $user): bool
    {
        // Public posts are visible to everyone
        if ($this->visibility === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        // Own posts are always visible
        if ($this->user_id === $user->id) {
            return true;
        }

        // Friends-only posts
        if ($this->visibility === 'friends') {
            return $this->user->isFriendsWith($user);
        }

        // Private posts only visible to owner
        return false;
    }

    public function scopeVisibleTo($query, ?User $user)
    {
        if (!$user) {
            return $query->where('visibility', 'public');
        }

        return $query->where(function ($q) use ($user) {
            $q->where('visibility', 'public')
              ->orWhere('user_id', $user->id)
              ->orWhere(function ($q2) use ($user) {
                  $q2->where('visibility', 'friends')
                     ->whereIn('user_id', function ($subQuery) use ($user) {
                         // Get IDs of friends
                         $subQuery->select('target_id')
                             ->from('connections')
                             ->where('user_id', $user->id)
                             ->where('type', 'friend')
                             ->where('status', 'accepted')
                             ->union(
                                 \DB::table('connections')
                                     ->select('user_id')
                                     ->where('target_id', $user->id)
                                     ->where('type', 'friend')
                                     ->where('status', 'accepted')
                             );
                     });
              });
        });
    }

    public function scopeFeed($query, User $user)
    {
        // Posts from user, friends, and following
        $friendIds = $user->friends()->pluck('id');
        $followingIds = $user->following()->pluck('id');

        $relevantUserIds = collect([$user->id])
            ->merge($friendIds)
            ->merge($followingIds)
            ->unique();

        return $query->whereIn('user_id', $relevantUserIds)
            ->visibleTo($user)
            ->whereNull('group_id')
            ->where('status', 'published')
            ->orderByDesc('created_at');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Save/Bookmark methods
    public function isSavedBy(User $user): bool
    {
        return $this->savedBy()->where('user_id', $user->id)->exists();
    }

    public function toggleSave(User $user): bool
    {
        return SavedPost::toggle($user, $this);
    }

    // Edit methods
    public function edit(string $newBody, ?string $ip = null): void
    {
        // Save the old version
        $this->edits()->create([
            'previous_body' => $this->body,
            'previous_media' => $this->media,
            'edited_by_ip' => $ip,
        ]);

        // Update the post
        $this->update([
            'body' => $newBody,
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        // Re-process mentions
        Mention::processMentions($this, $newBody);
    }

    // Location methods
    public function hasLocation(): bool
    {
        return !empty($this->location_name);
    }

    // Scheduling methods
    public function schedule(\DateTime $scheduledAt): void
    {
        $this->update([
            'scheduled_at' => $scheduledAt,
            'status' => 'scheduled',
        ]);
    }

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'scheduled_at' => null,
        ]);
    }

    public function saveDraft(): void
    {
        $this->update(['status' => 'draft']);
    }

    // Memories - posts from this day in previous years
    public static function getMemories(User $user): \Illuminate\Support\Collection
    {
        $today = now();

        return self::where('user_id', $user->id)
            ->whereMonth('created_at', $today->month)
            ->whereDay('created_at', $today->day)
            ->whereYear('created_at', '<', $today->year)
            ->published()
            ->with(['user.profile'])
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn($post) => $post->created_at->year);
    }

    // Link preview fetching
    public function fetchLinkPreview(): ?array
    {
        if (!$this->link_url) {
            return null;
        }

        try {
            $html = @file_get_contents($this->link_url);
            if (!$html) return null;

            $doc = new \DOMDocument();
            @$doc->loadHTML($html);
            $xpath = new \DOMXPath($doc);

            $title = $xpath->query('//meta[@property="og:title"]/@content')->item(0)?->nodeValue
                ?? $xpath->query('//title')->item(0)?->nodeValue ?? null;

            $description = $xpath->query('//meta[@property="og:description"]/@content')->item(0)?->nodeValue
                ?? $xpath->query('//meta[@name="description"]/@content')->item(0)?->nodeValue ?? null;

            $image = $xpath->query('//meta[@property="og:image"]/@content')->item(0)?->nodeValue ?? null;

            $preview = [
                'title' => $title,
                'description' => $description,
                'image' => $image,
                'url' => $this->link_url,
            ];

            $this->update(['link_preview' => $preview]);

            return $preview;
        } catch (\Exception $e) {
            return null;
        }
    }
}
