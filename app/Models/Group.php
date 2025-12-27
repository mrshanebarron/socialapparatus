<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $avatar
 * @property string|null $cover_photo
 * @property string $privacy
 * @property string $join_approval
 * @property bool $allow_member_posts
 * @property bool $allow_member_invites
 * @property-read int|null $members_count
 * @property int $posts_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $avatar_url
 * @property-read string|null $cover_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupInvitation> $invitations
 * @property-read int|null $invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupMember> $members
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group visibleTo(?\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereAllowMemberInvites($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereAllowMemberPosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereCoverPhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereJoinApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereMembersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group wherePostsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group wherePrivacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'avatar',
        'cover_photo',
        'privacy',
        'join_approval',
        'allow_member_posts',
        'allow_member_invites',
    ];

    protected $casts = [
        'allow_member_posts' => 'boolean',
        'allow_member_invites' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Group $group) {
            if (!$group->slug) {
                $group->slug = Str::slug($group->name);
                $originalSlug = $group->slug;
                $count = 1;
                while (static::where('slug', $group->slug)->exists()) {
                    $group->slug = $originalSlug . '-' . $count++;
                }
            }
        });

        static::created(function (Group $group) {
            // Add owner as admin member
            $group->members()->create([
                'user_id' => $group->owner_id,
                'role' => 'admin',
                'status' => 'approved',
                'joined_at' => now(),
            ]);
            $group->increment('members_count');
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(GroupInvitation::class);
    }

    public function approvedMembers()
    {
        return $this->members()->where('status', 'approved');
    }

    public function pendingMembers()
    {
        return $this->members()->where('status', 'pending');
    }

    public function admins()
    {
        return $this->members()->where('role', 'admin')->where('status', 'approved');
    }

    public function moderators()
    {
        return $this->members()->whereIn('role', ['admin', 'moderator'])->where('status', 'approved');
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200&background=6366f1&color=ffffff';
    }

    public function getCoverPhotoUrlAttribute(): ?string
    {
        if ($this->cover_photo) {
            return asset('storage/' . $this->cover_photo);
        }
        return null;
    }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->where('status', 'approved')->exists();
    }

    public function isPendingMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->where('status', 'pending')->exists();
    }

    public function isAdmin(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->where('role', 'admin')->where('status', 'approved')->exists();
    }

    public function isModerator(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->whereIn('role', ['admin', 'moderator'])->where('status', 'approved')->exists();
    }

    public function canView(User $user = null): bool
    {
        if ($this->privacy === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($this->privacy === 'private') {
            return true; // Private groups are visible but content is hidden
        }

        // Secret groups only visible to members
        return $this->isMember($user);
    }

    public function canViewContent(User $user = null): bool
    {
        if ($this->privacy === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $this->isMember($user);
    }

    public function join(User $user): ?GroupMember
    {
        if ($this->isMember($user) || $this->isPendingMember($user)) {
            return null;
        }

        $status = $this->join_approval === 'auto' ? 'approved' : 'pending';

        $member = $this->members()->create([
            'user_id' => $user->id,
            'role' => 'member',
            'status' => $status,
            'joined_at' => $status === 'approved' ? now() : null,
        ]);

        if ($status === 'approved') {
            $this->increment('members_count');
        }

        return $member;
    }

    public function leave(User $user): void
    {
        if ($user->id === $this->owner_id) {
            return; // Owner can't leave, must transfer ownership first
        }

        $member = $this->members()->where('user_id', $user->id)->first();
        if ($member && $member->status === 'approved') {
            $this->decrement('members_count');
        }
        $member?->delete();
    }

    public function scopePublic($query)
    {
        return $query->where('privacy', 'public');
    }

    public function scopeVisibleTo($query, ?User $user)
    {
        if (!$user) {
            return $query->where('privacy', 'public');
        }

        return $query->where(function ($q) use ($user) {
            $q->whereIn('privacy', ['public', 'private'])
              ->orWhereHas('members', function ($m) use ($user) {
                  $m->where('user_id', $user->id)->where('status', 'approved');
              });
        });
    }
}
