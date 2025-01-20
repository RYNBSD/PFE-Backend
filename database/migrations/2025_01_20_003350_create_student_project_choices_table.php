<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentProjectChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_project_choices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('option1')->nullable();
            $table->unsignedBigInteger('option2')->nullable();
            $table->unsignedBigInteger('option3')->nullable();
            $table->unsignedBigInteger('option4')->nullable();
            $table->unsignedBigInteger('option5')->nullable();
            $table->unsignedBigInteger('option6')->nullable();
            $table->unsignedBigInteger('option7')->nullable();
            $table->unsignedBigInteger('option8')->nullable();
            $table->unsignedBigInteger('option9')->nullable();
            $table->unsignedBigInteger('option10')->nullable();
            $table->timestamps();

            // Foreign key to users table for student
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');

            // Foreign keys for project choices
            $table->foreign('option1')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option2')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option3')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option4')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option5')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option6')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option7')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option8')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option9')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('option10')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_project_choices');
    }
}