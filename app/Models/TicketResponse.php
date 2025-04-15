<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'message',
        'is_private',
        'attachments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_private' => 'boolean',
        'attachments' => 'json',
    ];

    /**
     * Get the ticket that owns the response.
     */
    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class);
    }

    /**
     * Get the user that owns the response.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include public responses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Scope a query to only include private responses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    /**
     * Check if the response is private.
     *
     * @return bool
     */
    public function isPrivate()
    {
        return $this->is_private;
    }

    /**
     * Check if the response is from a staff member.
     *
     * @return bool
     */
    public function isFromStaff()
    {
        return $this->user && $this->user->isStaff();
    }

    /**
     * Check if the response is from the ticket owner.
     *
     * @return bool
     */
    public function isFromTicketOwner()
    {
        return $this->user_id === $this->supportTicket->user_id;
    }

    /**
     * Get the attachment URLs.
     *
     * @return array
     */
    public function getAttachmentUrlsAttribute()
    {
        if (!$this->attachments) {
            return [];
        }

        return array_map(function ($attachment) {
            return asset('storage/' . $attachment);
        }, $this->attachments);
    }
}