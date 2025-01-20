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
        Schema::table('project_supervisors', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['teacher_id']);
            // Change `teacher_id` to reference the `users` table
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('project_supervisors', function (Blueprint $table) {
            // Drop the foreign key referencing the `users` table
            $table->dropForeign(['teacher_id']);
            // Change `teacher_id` to reference the `teachers` table again
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }
};
