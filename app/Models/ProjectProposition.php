<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectProposition extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectPropositionFactory> */
    use HasFactory;
    protected $fillable = [
        'status',
    ];
    protected function cast()
    {
        return [
            "status" => ProjectStatus::class,
        ];
    }
    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    protected function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'foreign_key', 'validated_by');
    }
}
