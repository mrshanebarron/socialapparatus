<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $conversation_id
 * @property int $user_id
 * @property string|null $body
 * @property string $type
 * @property array<array-key, mixed>|null $attachments
 * @property int|null $reply_to_id
 * @property \Illuminate\Support\Carbon|null $edited_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $gif_url
 * @property string|null $voice_note
 * @property-read \App\Models\Conversation $conversation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MessageReaction> $reactions
 * @property-read int|null $reactions_count
 * @property-read Message|null $replyTo
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereEditedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereGifUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereReplyToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereVoiceNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message withoutTrashed()
 * @mixin \Eloquent
 */
class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
        'type',
        'attachments',
        'gif_url',
        'voice_note',
        'reply_to_id',
        'edited_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'edited_at' => 'datetime',
    ];

    public function reactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MessageReaction::class);
    }

    public function toggleReaction(User $user, string $emoji): ?MessageReaction
    {
        $existing = $this->reactions()->where('user_id', $user->id)->first();

        if ($existing) {
            if ($existing->emoji === $emoji) {
                $existing->delete();
                return null;
            }
            $existing->update(['emoji' => $emoji]);
            return $existing;
        }

        return $this->reactions()->create([
            'user_id' => $user->id,
            'emoji' => $emoji,
        ]);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function edit(string $newBody): void
    {
        $this->update([
            'body' => $newBody,
            'edited_at' => now(),
        ]);
    }

    public function isFromUser(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}
