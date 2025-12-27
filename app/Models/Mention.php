<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $mentionable_type
 * @property int $mentionable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $mentionable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mention newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mention newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mention query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mention whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mention whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mention whereMentionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mention whereMentionableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mention whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mention whereUserId($value)
 * @mixin \Eloquent
 */
class Mention extends Model
{
    protected $fillable = [
        'user_id',
        'mentionable_type',
        'mentionable_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mentionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Parse text and extract mentioned usernames
     */
    public static function parseMentions(string $text): array
    {
        preg_match_all('/@(\w+)/', $text, $matches);
        return array_unique($matches[1] ?? []);
    }

    /**
     * Process mentions in text and create mention records
     */
    public static function processMentions(Model $mentionable, string $text): array
    {
        $usernames = self::parseMentions($text);
        $mentionedUsers = [];

        foreach ($usernames as $username) {
            // Try to find user by username in profile
            $profile = Profile::where('username', $username)->first();
            if ($profile) {
                $mentionable->mentions()->firstOrCreate([
                    'user_id' => $profile->user_id,
                ]);
                $mentionedUsers[] = $profile->user;

                // Create notification for the mentioned user
                Notification::create([
                    'user_id' => $profile->user_id,
                    'from_user_id' => $mentionable->user_id,
                    'type' => 'mention',
                    'notifiable_type' => get_class($mentionable),
                    'notifiable_id' => $mentionable->id,
                    'message' => 'mentioned you in a ' . strtolower(class_basename($mentionable)),
                ]);
            }
        }

        return $mentionedUsers;
    }

    /**
     * Convert @mentions in text to clickable links
     */
    public static function renderMentions(string $text): string
    {
        return preg_replace_callback('/@(\w+)/', function ($matches) {
            $username = $matches[1];
            $profile = Profile::where('username', $username)->first();

            if ($profile) {
                $url = route('profile.view', $username);
                return '<a href="' . $url . '" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">@' . $username . '</a>';
            }

            return '@' . $username;
        }, e($text));
    }
}
