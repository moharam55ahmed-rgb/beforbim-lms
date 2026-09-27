<?php

namespace App\Modules\SupportTicket\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAttachment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ticket_message_id',
        'file_name',
        'file_path',
        'file_size_bytes',
        'mime_type',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size_bytes' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TicketAttachment $att) {
            if (empty($att->created_at)) {
                $att->created_at = now();
            }
        });
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(TicketMessage::class, 'ticket_message_id');
    }
}
