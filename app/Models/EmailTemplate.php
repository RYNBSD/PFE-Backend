<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\EmailTemplateFactory> */
    use HasFactory;
    protected $fillable = [
        'subject',
        'content'
    ];
    protected function cast(){
        return [
            "created_at"=>"datetime",
            "updated_at"=>"datetime",
            "deleted_at"=>"datetime"
        ];
    }
    
}
