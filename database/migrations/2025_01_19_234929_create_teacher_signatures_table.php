<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherSignaturesTable extends Migration
{
    public function up()
    {
        Schema::create('teacher_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Reference to users table
            $table->text('signature'); // Store the base64 string as text
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_signatures');
    }
}
