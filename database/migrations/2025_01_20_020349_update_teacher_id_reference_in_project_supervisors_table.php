<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTeacherIdReferenceInProjectSupervisorsTable extends Migration
{
    public function up()
    {
        // Step 1: Drop the existing foreign key (if any)
        Schema::table('project_supervisors', function (Blueprint $table) {
            // Check if there is an existing foreign key constraint and drop it
            $table->dropForeign(['teacher_id']);
        });

        // Step 2: Modify the teacher_id to reference the users table
        Schema::table('project_supervisors', function (Blueprint $table) {
            // Drop the teacher_id column (optional step if you want to change it fully)
            $table->dropColumn('teacher_id');

            // Add a new teacher_id column referencing the users table
            $table->unsignedBigInteger('teacher_id')->nullable()->after('project_id');

            // Foreign key referencing the users table
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Reverse the changes by dropping the foreign key and restoring the teacher reference
        Schema::table('project_supervisors', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
            $table->unsignedBigInteger('teacher_id')->nullable()->after('project_id');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }
}
