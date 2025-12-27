<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $conversation_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $last_read_at
 * @property bool $is_muted
 * @property \Illuminate\Support\Carbon|null $joined_at
 * @property \Illuminate\Support\Carbon|null $left_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_typing
 * @property \Illuminate\Support\Carbon|null $typing_at
 * @property-read \App\Models\Conversation $conversation
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereIsMuted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereIsTyping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereJoinedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereLastReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereLeftAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereTypingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationParticipant whereUserId($value)
 * @mixin \Eloquent
 */
class ConversationParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'last_read_at',
        'is_muted',
        'joined_at',
        'left_at',
        'is_typing',
        'typing_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'typing_at' => 'datetime',
        'is_muted' => 'boolean',
        'is_typing' => 'boolean',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leave(): void
    {
        $this->update(['left_at' => now()]);
    }

    public function toggleMute(): void
    {
        $this->update(['is_muted' => !$this->is_muted]);
    }

    public function startTyping(): void
    {
        $this->update([
            'is_typing' => true,
            'typing_at' => now(),
        ]);
    }

    public function stopTyping(): void
    {
        $this->update(['is_typing' => false]);
    }

    public function isActivelyTyping(): bool
    {
        // Consider typing inactive if more than 5 seconds old
        return $this->is_typing && $this->typing_at && $this->typing_at->diffInSeconds(now()) < 5;
    }
}
