<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    /** @use HasFactory<\Database\Factories\GroupFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [];
    protected function groupMember():HasMany{
        return $this->hasMany(GroupMember::class);
    }
    protected function projectSubmit():HasOne{
        return $this->hasOne(ProjectSubmit::class);
    }
    protected function cast(){
        return [
            "created_at"=>"datetime",
            "updated_at"=>"datetime",
            "deleted_at"=>"datetime"
        ];
    }
}
