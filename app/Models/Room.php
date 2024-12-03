<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room'
    ];
    protected function cast()
    {
        return [
            "created_at" => "datetime",
            "updated_at" => "datetime",
            "deleted_at" => "datetime"
        ];
    }
}
