<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
    ];
    protected function cast()
    {
        return [
            "type" => ProjectType::class,
            "status" => ProjectStatus::class,
            "created_at" => "datetime",
            "updated_at" => "datetime",
            "deleted_at" => "datetime"
        ];
    }
    protected function projectProposition(): HasOne
    {
        return $this->hasOne(ProjectProposition::class);
    }
    protected function projectNote(): HasOne
    {
        return $this->hasOne(ProjectNote::class);
    }
    protected function projectJury(): HasMany
    {
        return $this->hasMany(ProjectJury::class);
    }
}
