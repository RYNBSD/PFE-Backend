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
        Schema::create('group_members', function (Blueprint $table) {
            $table->unsignedBigInteger("student_id");
            $table->unsignedBigInteger("group_id");
            $table->foreign("student_id")->references("user_id")->on("students");
            $table->foreign("group_id")->references("group_id")->on("groups");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
