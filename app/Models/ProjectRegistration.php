<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRegistration extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectRegistrationFactory> */
    use HasFactory;
    protected $fillable = [ 
        'project_id',
    ];
    protected function cast()
    {
        return [
            "start_date" => "datetime",
            "end_date" => "datetime"
        ];
    }
}
