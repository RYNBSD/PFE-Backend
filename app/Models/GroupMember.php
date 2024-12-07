<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupMember extends Model
{
    /** @use HasFactory<\Database\Factories\GroupMemberFactory> */
    use HasFactory;
    protected function group() : BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
    protected function student() : BelongsTo
    {
        return $this->belongsTo(Student::class);
    }


}
