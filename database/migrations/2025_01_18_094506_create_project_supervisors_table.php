<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('project_supervisors', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('project_id');
        $table->unsignedBigInteger('teacher_id');
        $table->enum('role', ['SUPERVISOR', 'CO_SUPERVISOR']);
        $table->timestamps();

        $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        $table->foreign('teacher_id')->references('user_id')->on('teachers')->onDelete('cascade');
    });
}

    public function down()
    {
        Schema::dropIfExists('project_supervisors');
    }

};
