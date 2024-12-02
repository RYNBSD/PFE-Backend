<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectPresentation extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectPresentationFactory> */
    use HasFactory;
    protected $fillable = [
        'date',
    ];
    protected function cast(){
        return [
            "date"=>"datetime"
        ];
    }
    protected function user():HasMany{
        return $this->hasMany(User::class);
    }
    protected function project():BelongsTo{
        return $this->belongsTo(Project::class);
    }
    protected function room():HasOne{
        return $this->hasOne(Room::class);
    }
}
