<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'number'
    ];
    public function user():BelongsTo{
        return $this->belongsTo(user::class);
    }

}
