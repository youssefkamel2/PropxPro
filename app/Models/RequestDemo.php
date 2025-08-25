<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RequestDemo extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'phone',
        'email',
        'real_estate_experience',
        'monthly_budget',
        'preferred_datetime',
        'google_event_id',
        'google_meet_link',
        'google_event_html_link',
        'meet_status',
        'status',
        'failure_reason',
        'last_checked_at',
        'scheduled_at',
        'email_sent_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $casts = [
        'preferred_datetime' => 'datetime',

    ];

    protected $appends = [
        'full_name',
        'formatted_datetime',
        'public_id',
    ];

    public function getPublicIdAttribute()
    {
        return $this->uuid;
    }

    // Possible status values
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_DECLINED = 'declined';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';

    // Possible meet status values
    const MEET_STATUS_PENDING = 'pending';
    const MEET_STATUS_SCHEDULED = 'scheduled';
    const MEET_STATUS_AWAITING_CONFIRMATION = 'awaiting_confirmation';
    const MEET_STATUS_CONFIRMED = 'confirmed';
    const MEET_STATUS_DECLINED = 'declined';
    const MEET_STATUS_COMPLETED = 'completed';
    const MEET_STATUS_EXPIRED = 'expired';
    const MEET_STATUS_CANCELLED = 'cancelled';
    const MEET_STATUS_FAILED = 'failed';

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Accessor for formatted datetime
    public function getFormattedDatetimeAttribute(): string|null
    {
        return $this->preferred_datetime ?
            $this->preferred_datetime->format('F j, Y \a\t g:i A T') :
            null;
    }

    // Status scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', self::STATUS_DECLINED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    // Meet status scopes
    public function scopeMeetScheduled($query)
    {
        return $query->where('meet_status', self::MEET_STATUS_SCHEDULED);
    }

    public function scopeMeetAwaitingConfirmation($query)
    {
        return $query->where('meet_status', self::MEET_STATUS_AWAITING_CONFIRMATION);
    }

    public function scopeMeetConfirmed($query)
    {
        return $query->where('meet_status', self::MEET_STATUS_CONFIRMED);
    }

    public function scopeMeetDeclined($query)
    {
        return $query->where('meet_status', self::MEET_STATUS_DECLINED);
    }

    public function scopeMeetCompleted($query)
    {
        return $query->where('meet_status', self::MEET_STATUS_COMPLETED);
    }

    public function scopeMeetExpired($query)
    {
        return $query->where('meet_status', self::MEET_STATUS_EXPIRED);
    }

    public function scopeMeetCancelled($query)
    {
        return $query->where('meet_status', self::MEET_STATUS_CANCELLED);
    }

    public function scopeMeetFailed($query)
    {
        return $query->where('meet_status', self::MEET_STATUS_FAILED);
    }

    // Status check methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isDeclined(): bool
    {
        return $this->status === self::STATUS_DECLINED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    // Meet status check methods
    public function isMeetScheduled(): bool
    {
        return $this->meet_status === self::MEET_STATUS_SCHEDULED;
    }

    public function isMeetAwaitingConfirmation(): bool
    {
        return $this->meet_status === self::MEET_STATUS_AWAITING_CONFIRMATION;
    }

    public function isMeetConfirmed(): bool
    {
        return $this->meet_status === self::MEET_STATUS_CONFIRMED;
    }

    public function isMeetDeclined(): bool
    {
        return $this->meet_status === self::MEET_STATUS_DECLINED;
    }

    public function isMeetCompleted(): bool
    {
        return $this->meet_status === self::MEET_STATUS_COMPLETED;
    }

    public function isMeetExpired(): bool
    {
        return $this->meet_status === self::MEET_STATUS_EXPIRED;
    }

    public function isMeetCancelled(): bool
    {
        return $this->meet_status === self::MEET_STATUS_CANCELLED;
    }

    public function isMeetFailed(): bool
    {
        return $this->meet_status === self::MEET_STATUS_FAILED;
    }
}