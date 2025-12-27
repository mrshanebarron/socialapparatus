<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property int $is_private
 * @property int $require_follow_approval
 * @property int $digest_enabled
 * @property int $is_verified
 * @property string|null $verification_badge_type
 * @property string|null $verified_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AccountDeletion> $accountDeletions
 * @property-read int|null $account_deletions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivityLog> $activityLogs
 * @property-read int|null $activity_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Album> $albums
 * @property-read int|null $albums_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Article> $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $askedQuestions
 * @property-read int|null $asked_questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Badge> $badges
 * @property-read int|null $badges_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Block> $blockedBy
 * @property-read int|null $blocked_by_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Block> $blocks
 * @property-read int|null $blocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Board> $boards
 * @property-read int|null $boards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CloseFriend> $closeFriends
 * @property-read int|null $close_friends_count
 * @property-read \App\Models\CoinBalance|null $coinBalance
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CoinTransaction> $coinTransactions
 * @property-read int|null $coin_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Collection> $collections
 * @property-read int|null $collections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConversationParticipant> $conversationParticipants
 * @property-read int|null $conversation_participants_count
 * @property-read \App\Models\Team|null $currentTeam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DataExport> $dataExports
 * @property-read int|null $data_exports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventReminder> $eventReminders
 * @property-read int|null $event_reminders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventRsvp> $eventRsvps
 * @property-read int|null $event_rsvps_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupMember> $groupMemberships
 * @property-read int|null $group_memberships_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserInterest> $interests
 * @property-read int|null $interests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoginAlert> $loginAlerts
 * @property-read int|null $login_alerts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoginSession> $loginSessions
 * @property-read int|null $login_sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $ownedGroups
 * @property-read int|null $owned_groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupInvitation> $pendingGroupInvitations
 * @property-read int|null $pending_group_invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Connection> $receivedConnections
 * @property-read int|null $received_connections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Poke> $receivedPokes
 * @property-read int|null $received_pokes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $receivedQuestions
 * @property-read int|null $received_questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recommendation> $recommendations
 * @property-read int|null $recommendations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RestrictedUser> $restrictedUsers
 * @property-read int|null $restricted_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Connection> $sentConnections
 * @property-read int|null $sent_connections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Poke> $sentPokes
 * @property-read int|null $sent_pokes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Series> $series
 * @property-read int|null $series_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Story> $stories
 * @property-read int|null $stories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StoryHighlight> $storyHighlights
 * @property-read int|null $story_highlights_count
 * @property-read \App\Models\Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TrustedDevice> $trustedDevices
 * @property-read int|null $trusted_devices_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDigestEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsPrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRequireFollowApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVerificationBadgeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVerifiedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function getOrCreateProfile(): Profile
    {
        if (!$this->profile) {
            $this->profile()->create([
                'display_name' => $this->name,
            ]);
            $this->refresh();
        }
        return $this->profile;
    }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->profile()->create([
                'display_name' => $user->name,
            ]);
        });
    }

    // Connection relationships
    public function sentConnections(): HasMany
    {
        return $this->hasMany(Connection::class, 'user_id');
    }

    public function receivedConnections(): HasMany
    {
        return $this->hasMany(Connection::class, 'target_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'user_id');
    }

    public function blockedBy(): HasMany
    {
        return $this->hasMany(Block::class, 'blocked_id');
    }

    // Friend methods
    public function friends()
    {
        $sentFriends = $this->sentConnections()
            ->friends()
            ->accepted()
            ->with('target.profile')
            ->get()
            ->pluck('target');

        $receivedFriends = $this->receivedConnections()
            ->friends()
            ->accepted()
            ->with('user.profile')
            ->get()
            ->pluck('user');

        return $sentFriends->merge($receivedFriends);
    }

    public function isFriendsWith(User $user): bool
    {
        return $this->sentConnections()
            ->friends()
            ->accepted()
            ->where('target_id', $user->id)
            ->exists()
            || $this->receivedConnections()
                ->friends()
                ->accepted()
                ->where('user_id', $user->id)
                ->exists();
    }

    public function hasPendingFriendRequestFrom(User $user): bool
    {
        return $this->receivedConnections()
            ->friends()
            ->pending()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function hasPendingFriendRequestTo(User $user): bool
    {
        return $this->sentConnections()
            ->friends()
            ->pending()
            ->where('target_id', $user->id)
            ->exists();
    }

    public function sendFriendRequest(User $user): ?Connection
    {
        if ($this->id === $user->id) return null;
        if ($this->isFriendsWith($user)) return null;
        if ($this->hasPendingFriendRequestTo($user)) return null;
        if ($this->hasBlocked($user) || $user->hasBlocked($this)) return null;

        // If they already sent us a request, accept it
        if ($this->hasPendingFriendRequestFrom($user)) {
            $request = $this->receivedConnections()
                ->friends()
                ->pending()
                ->where('user_id', $user->id)
                ->first();
            $request->accept();
            return $request;
        }

        return $this->sentConnections()->create([
            'target_id' => $user->id,
            'type' => 'friend',
            'status' => 'pending',
        ]);
    }

    public function unfriend(User $user): void
    {
        Connection::removeFriendship($this, $user);
    }

    // Follow methods
    public function following()
    {
        return $this->sentConnections()
            ->follows()
            ->accepted()
            ->with('target.profile')
            ->get()
            ->pluck('target');
    }

    public function followers()
    {
        return $this->receivedConnections()
            ->follows()
            ->accepted()
            ->with('user.profile')
            ->get()
            ->pluck('user');
    }

    public function isFollowing(User $user): bool
    {
        return $this->sentConnections()
            ->follows()
            ->accepted()
            ->where('target_id', $user->id)
            ->exists();
    }

    public function follow(User $user): ?Connection
    {
        if ($this->id === $user->id) return null;
        if ($this->isFollowing($user)) return null;
        if ($this->hasBlocked($user) || $user->hasBlocked($this)) return null;

        $connection = $this->sentConnections()->create([
            'target_id' => $user->id,
            'type' => 'follow',
            'status' => 'accepted', // Follows are auto-accepted
        ]);

        // Update counts
        $this->profile?->increment('following_count');
        $user->profile?->increment('followers_count');

        return $connection;
    }

    public function unfollow(User $user): void
    {
        Connection::removeFollow($this, $user);
    }

    // Block methods
    public function hasBlocked(User $user): bool
    {
        return $this->blocks()->where('blocked_id', $user->id)->exists();
    }

    public function block(User $user, ?string $reason = null): ?Block
    {
        if ($this->id === $user->id) return null;
        if ($this->hasBlocked($user)) return null;

        // Remove any existing connections
        $this->unfriend($user);
        $this->unfollow($user);
        $user->unfollow($this);

        return $this->blocks()->create([
            'blocked_id' => $user->id,
            'reason' => $reason,
        ]);
    }

    public function unblock(User $user): void
    {
        $this->blocks()->where('blocked_id', $user->id)->delete();
    }

    // Pending friend requests
    public function pendingFriendRequests()
    {
        return $this->receivedConnections()
            ->friends()
            ->pending()
            ->with('user.profile')
            ->get();
    }

    public function pendingFriendRequestsCount(): int
    {
        return $this->receivedConnections()
            ->friends()
            ->pending()
            ->count();
    }

    // Group relationships
    public function ownedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function groupMemberships(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    public function groups()
    {
        return $this->groupMemberships()
            ->where('status', 'approved')
            ->with('group')
            ->get()
            ->pluck('group');
    }

    public function pendingGroupInvitations(): HasMany
    {
        return $this->hasMany(GroupInvitation::class, 'invitee_id')
            ->where('status', 'pending');
    }

    // Messaging relationships
    public function conversationParticipants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function conversations()
    {
        return Conversation::whereHas('participants', function ($q) {
            $q->where('user_id', $this->id)->whereNull('left_at');
        })->orderByDesc('last_message_at');
    }

    public function unreadMessagesCount(): int
    {
        $count = 0;
        foreach ($this->conversations()->get() as $conversation) {
            $count += $conversation->getUnreadCountFor($this);
        }
        return $count;
    }

    public function startConversationWith(User $user): Conversation
    {
        return Conversation::findOrCreateDirect($this, $user);
    }

    // Media relationships
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    // Posts relationship
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Articles relationship
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    // Notifications relationship
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Stories relationship
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    // Events relationship
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    // Event RSVPs relationship
    public function eventRsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    // Close Friends relationship
    public function closeFriends(): HasMany
    {
        return $this->hasMany(CloseFriend::class);
    }

    public function closeFriendUsers()
    {
        return User::whereIn('id', $this->closeFriends()->pluck('friend_id'));
    }

    public function isCloseFriendOf(User $user): bool
    {
        return $user->closeFriends()->where('friend_id', $this->id)->exists();
    }

    public function addCloseFriend(User $user): ?CloseFriend
    {
        if ($this->id === $user->id) return null;
        if ($this->closeFriends()->where('friend_id', $user->id)->exists()) return null;

        return $this->closeFriends()->create(['friend_id' => $user->id]);
    }

    public function removeCloseFriend(User $user): void
    {
        $this->closeFriends()->where('friend_id', $user->id)->delete();
    }

    // Restricted Users relationship
    public function restrictedUsers(): HasMany
    {
        return $this->hasMany(RestrictedUser::class);
    }

    public function isRestricted(User $user): bool
    {
        return $this->restrictedUsers()->where('restricted_user_id', $user->id)->exists();
    }

    public function restrict(User $user, ?string $reason = null): ?RestrictedUser
    {
        if ($this->id === $user->id) return null;
        if ($this->isRestricted($user)) return null;

        return $this->restrictedUsers()->create([
            'restricted_user_id' => $user->id,
            'reason' => $reason,
        ]);
    }

    public function unrestrict(User $user): void
    {
        $this->restrictedUsers()->where('restricted_user_id', $user->id)->delete();
    }

    // Badges relationship
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at', 'displayed')
            ->withTimestamps();
    }

    public function displayedBadges()
    {
        return $this->badges()->wherePivot('displayed', true);
    }

    public function awardBadge(Badge $badge): void
    {
        if (!$this->badges()->where('badge_id', $badge->id)->exists()) {
            $this->badges()->attach($badge->id, ['earned_at' => now()]);
        }
    }

    // Login Sessions relationship
    public function loginSessions(): HasMany
    {
        return $this->hasMany(LoginSession::class);
    }

    public function trustedDevices(): HasMany
    {
        return $this->hasMany(TrustedDevice::class);
    }

    public function loginAlerts(): HasMany
    {
        return $this->hasMany(LoginAlert::class);
    }

    // Event Reminders relationship
    public function eventReminders(): HasMany
    {
        return $this->hasMany(EventReminder::class);
    }

    // Collections relationship
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    // User Interests for recommendations
    public function interests(): HasMany
    {
        return $this->hasMany(UserInterest::class);
    }

    // Recommendations relationship
    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    // Activity Log relationship
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function logActivity(string $action, string $category, string $description, $subject = null, array $properties = []): ActivityLog
    {
        return $this->activityLogs()->create([
            'action' => $action,
            'category' => $category,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // Data Exports relationship
    public function dataExports(): HasMany
    {
        return $this->hasMany(DataExport::class);
    }

    // Account Deletions relationship
    public function accountDeletions(): HasMany
    {
        return $this->hasMany(AccountDeletion::class);
    }

    // Pokes relationships
    public function sentPokes(): HasMany
    {
        return $this->hasMany(Poke::class, 'poker_id');
    }

    public function receivedPokes(): HasMany
    {
        return $this->hasMany(Poke::class, 'poked_id');
    }

    public function poke(User $user): ?Poke
    {
        if ($this->id === $user->id) return null;
        if ($this->hasBlocked($user) || $user->hasBlocked($this)) return null;

        $existingPoke = Poke::where('poker_id', $this->id)
            ->where('poked_id', $user->id)
            ->first();

        if ($existingPoke) {
            $existingPoke->touch();
            $existingPoke->update(['is_seen' => false]);
            return $existingPoke;
        }

        return $this->sentPokes()->create([
            'poked_id' => $user->id,
        ]);
    }

    public function pokeBack(Poke $poke): ?Poke
    {
        if ($poke->poked_id !== $this->id) return null;

        $poke->update(['poked_back_at' => now()]);

        return $this->poke($poke->poker);
    }

    // Questions/AMA relationships
    public function askedQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'asker_id');
    }

    public function receivedQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'recipient_id');
    }

    public function askQuestion(User $recipient, string $question, bool $anonymous = false): ?Question
    {
        if ($this->id === $recipient->id) return null;
        if ($this->hasBlocked($recipient) || $recipient->hasBlocked($this)) return null;

        $profile = $recipient->profile;
        if (!$profile?->allow_questions) return null;
        if ($anonymous && !$profile?->allow_anonymous_questions) return null;

        return $this->askedQuestions()->create([
            'recipient_id' => $recipient->id,
            'question' => $question,
            'is_anonymous' => $anonymous,
        ]);
    }

    // Coin Balance relationship
    public function coinBalance(): HasOne
    {
        return $this->hasOne(CoinBalance::class);
    }

    public function coinTransactions(): HasMany
    {
        return $this->hasMany(CoinTransaction::class);
    }

    // Boards relationship
    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }

    // Series relationship
    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
    }

    // Story Highlights relationship
    public function storyHighlights(): HasMany
    {
        return $this->hasMany(StoryHighlight::class);
    }

    // Check if user is a moderator
    public function isModerator(): bool
    {
        return $this->is_admin ?? false;
    }

    // Check if user can fact-check content
    public function canFactCheck(): bool
    {
        return $this->is_admin ?? false;
    }

    // Friend suggestions based on mutual friends
    public function getFriendSuggestions($limit = 10)
    {
        $friendIds = $this->friends()->pluck('id')->toArray();
        $blockedIds = $this->blocks()->pluck('blocked_id')->toArray();
        $blockedByIds = $this->blockedBy()->pluck('user_id')->toArray();
        $excludeIds = array_merge($friendIds, $blockedIds, $blockedByIds, [$this->id]);

        // Get pending request user IDs to exclude
        $pendingSentIds = $this->sentConnections()->friends()->pending()->pluck('target_id')->toArray();
        $pendingReceivedIds = $this->receivedConnections()->friends()->pending()->pluck('user_id')->toArray();
        $excludeIds = array_merge($excludeIds, $pendingSentIds, $pendingReceivedIds);

        return User::whereIn('id', function ($query) use ($friendIds) {
            // Get friends of friends
            $query->select('target_id')
                ->from('connections')
                ->whereIn('user_id', $friendIds)
                ->where('type', 'friend')
                ->where('status', 'accepted')
                ->union(
                    \DB::table('connections')
                        ->select('user_id')
                        ->whereIn('target_id', $friendIds)
                        ->where('type', 'friend')
                        ->where('status', 'accepted')
                );
        })
        ->whereNotIn('id', $excludeIds)
        ->withCount(['receivedConnections as mutual_friends_count' => function ($q) use ($friendIds) {
            $q->whereIn('user_id', $friendIds)
              ->where('type', 'friend')
              ->where('status', 'accepted');
        }])
        ->orderByDesc('mutual_friends_count')
        ->limit($limit)
        ->get();
    }
}
