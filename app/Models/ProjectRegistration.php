<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRegistration extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectRegistrationFactory> */
    use HasFactory;
    //shouldn't belong to a project in particular 
    protected function cast(){
        return [
            "start_date"=>"datetime",
            "end_date"=>"datetime"
        ];
    }
}
