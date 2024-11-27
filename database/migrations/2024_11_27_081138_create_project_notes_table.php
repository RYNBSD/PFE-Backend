<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_notes', function (Blueprint $table) {
            $table->unsignedBigInteger("student_id");
            $table->unsignedBigInteger("project_id");
            $table->decimal("note", 2, 2);
            $table->foreign("student_id")->references("user_id")->on("students");
            $table->foreign("project_id")->references("id")->on("projects");
            $table->primary(["student_id", "project_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_notes');
    }
};
