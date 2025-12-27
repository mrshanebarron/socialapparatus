<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $verification_request_id
 * @property int $user_id
 * @property string $action
 * @property string|null $notes
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\VerificationRequest $verificationRequest
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VerificationAuditLog whereVerificationRequestId($value)
 * @mixin \Eloquent
 */
class VerificationAuditLog extends Model
{
    use HasFactory;

    protected $fillable = ['verification_request_id', 'user_id', 'action', 'notes', 'metadata'];

    protected $casts = ['metadata' => 'array'];

    public function verificationRequest(): BelongsTo
    {
        return $this->belongsTo(VerificationRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(VerificationRequest $request, User $user, string $action, ?string $notes = null, array $metadata = []): self
    {
        return self::create([
            'verification_request_id' => $request->id,
            'user_id' => $user->id,
            'action' => $action,
            'notes' => $notes,
            'metadata' => $metadata,
        ]);
    }
}
