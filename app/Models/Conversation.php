<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $type
 * @property string|null $name
 * @property string|null $avatar
 * @property int|null $group_id
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $last_message_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConversationParticipant> $activeParticipants
 * @property-read int|null $active_participants_count
 * @property-read \App\Models\User|null $creator
 * @property-read string $avatar_url
 * @property-read string $display_name
 * @property-read \App\Models\Group|null $group
 * @property-read \App\Models\Message|null $latestMessage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConversationParticipant> $participants
 * @property-read int|null $participants_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereLastMessageAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'avatar',
        'group_id',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function activeParticipants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class)->whereNull('left_at');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->type === 'group' && $this->name) {
            return $this->name;
        }

        // For direct messages, show the other participant's name
        $otherParticipant = $this->participants()
            ->where('user_id', '!=', auth()->id())
            ->whereNull('left_at')
            ->with('user.profile')
            ->first();

        return $otherParticipant?->user?->profile?->display_name
            ?? $otherParticipant?->user?->name
            ?? 'Unknown';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->type === 'group') {
            if ($this->avatar) {
                return asset('storage/' . $this->avatar);
            }
            if ($this->group) {
                return $this->group->avatar_url;
            }
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'Group') . '&size=200&background=6366f1&color=ffffff';
        }

        // For direct messages, show the other participant's avatar
        $otherParticipant = $this->participants()
            ->where('user_id', '!=', auth()->id())
            ->whereNull('left_at')
            ->with('user.profile')
            ->first();

        return $otherParticipant?->user?->profile?->avatar_url
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->display_name) . '&size=200';
    }

    public function isParticipant(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->whereNull('left_at')->exists();
    }

    public function getUnreadCountFor(User $user): int
    {
        $participant = $this->participants()->where('user_id', $user->id)->first();
        if (!$participant || !$participant->last_read_at) {
            return $this->messages()->count();
        }

        return $this->messages()
            ->where('created_at', '>', $participant->last_read_at)
            ->where('user_id', '!=', $user->id)
            ->count();
    }

    public function markAsReadFor(User $user): void
    {
        $this->participants()
            ->where('user_id', $user->id)
            ->update(['last_read_at' => now()]);
    }

    public static function findOrCreateDirect(User $user1, User $user2): self
    {
        // Find existing direct conversation between these two users
        $conversation = static::where('type', 'direct')
            ->whereHas('participants', function ($q) use ($user1) {
                $q->where('user_id', $user1->id)->whereNull('left_at');
            })
            ->whereHas('participants', function ($q) use ($user2) {
                $q->where('user_id', $user2->id)->whereNull('left_at');
            })
            ->first();

        if ($conversation) {
            return $conversation;
        }

        // Create new conversation
        $conversation = static::create([
            'type' => 'direct',
            'created_by' => $user1->id,
        ]);

        $conversation->participants()->createMany([
            ['user_id' => $user1->id, 'joined_at' => now()],
            ['user_id' => $user2->id, 'joined_at' => now()],
        ]);

        return $conversation;
    }

    public static function createGroup(User $creator, string $name, array $participantIds = []): self
    {
        $conversation = static::create([
            'type' => 'group',
            'name' => $name,
            'created_by' => $creator->id,
        ]);

        // Add creator
        $conversation->participants()->create([
            'user_id' => $creator->id,
            'joined_at' => now(),
        ]);

        // Add other participants
        foreach ($participantIds as $userId) {
            if ($userId !== $creator->id) {
                $conversation->participants()->create([
                    'user_id' => $userId,
                    'joined_at' => now(),
                ]);
            }
        }

        return $conversation;
    }

    public function sendMessage(User $user, string $body, string $type = 'text', array $attachments = []): Message
    {
        $message = $this->messages()->create([
            'user_id' => $user->id,
            'body' => $body,
            'type' => $type,
            'attachments' => $attachments ?: null,
        ]);

        $this->update(['last_message_at' => now()]);
        $this->markAsReadFor($user);

        // Stop typing indicator when message is sent
        $this->setTyping($user, false);

        return $message;
    }

    public function setTyping(User $user, bool $isTyping): void
    {
        $participant = $this->participants()->where('user_id', $user->id)->first();
        if ($participant) {
            if ($isTyping) {
                $participant->startTyping();
            } else {
                $participant->stopTyping();
            }
        }
    }

    public function getTypingUsers(User $excludeUser): array
    {
        return $this->participants()
            ->where('user_id', '!=', $excludeUser->id)
            ->where('is_typing', true)
            ->where('typing_at', '>', now()->subSeconds(5))
            ->with('user')
            ->get()
            ->pluck('user.name')
            ->toArray();
    }

    public function getReadByUsers(Message $message, User $excludeUser): array
    {
        return $this->participants()
            ->where('user_id', '!=', $excludeUser->id)
            ->whereNotNull('last_read_at')
            ->where('last_read_at', '>=', $message->created_at)
            ->with('user')
            ->get()
            ->pluck('user')
            ->toArray();
    }
}
