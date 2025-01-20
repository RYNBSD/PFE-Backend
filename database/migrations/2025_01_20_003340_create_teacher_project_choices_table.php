<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherProjectChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_project_choices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('option1')->nullable();
            $table->unsignedBigInteger('option2')->nullable();
            $table->unsignedBigInteger('option3')->nullable();
            $table->unsignedBigInteger('option4')->nullable();
            $table->unsignedBigInteger('option5')->nullable();
            $table->timestamps();

            // Foreign key to users table for teacher
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');

            // Foreign keys for project choices
            $table->foreign('option1')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option2')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option3')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option4')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option5')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_project_choices');
    }
}