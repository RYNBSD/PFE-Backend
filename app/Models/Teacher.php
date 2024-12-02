<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\TeacherGrade;
class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;
    protected $fillable = [
        'grade',
        'recruitment_date'
    ];
    public function user():BelongsTo{
        return $this->belongsTo(BelongsTo::class);
    }
    protected function casts(): array
    {
        return [
            'grade'=>TeacherGrade::class,
            'recruitment_date'=>'datetime'
        ];
    }
}
