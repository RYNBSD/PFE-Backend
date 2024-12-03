<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'major',
        'average_score'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
