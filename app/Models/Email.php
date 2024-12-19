<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\EmailStatus;

class Email extends Model
{
    /** @use HasFactory<\Database\Factories\EmailFactory> */
    use HasFactory;
    protected $fillable = [
        'subject',
        'content',
        'status',
        'receiver_id',
        'admin_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => EmailStatus::class,
            // 'sent_at' => 'datetime',
            'created_at' => 'datetime'
        ];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "receiver_id");
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, null, "user_id");
    }
}
