<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPropositionFeedback extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectPropositionFeedbackFactory> */
    use HasFactory;
    protected $fillable = [
        "project_proposition_id",
        'feedback',
    ];
    protected function cast()
    {
        return [
            "created_at" => "datetime",
            "updated_at" => "datetime",
        ];
    }
    protected function projectProposition(): BelongsTo
    {
        return $this->belongsTo(ProjectProposition::class);
    }
}
