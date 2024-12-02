<?php

namespace App\Models;

use App\Enums\ProjectJuriesRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class ProjectJury extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectJuryFactory> */
    use HasFactory;
    protected $fillable = [
        'role',
    ];

    protected function teacher():BelongsToMany{
        return $this->belongsToMany(Teacher::class);
    }
    protected function project():BelongsTo{
        return $this->belongsTo(Project::class);
    }
    protected function cast(){
        return [
            'role'=>ProjectJuriesRole::class,
        ];
    }

}
