<?php

use App\Enums\ProjectJuriesRole;
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
        Schema::create('project_juries', function (Blueprint $table) {
            $table->unsignedBigInteger("teacher_id");
            $table->unsignedBigInteger("project_id");
            $table->enum("role", ProjectJuriesRole::values());
            $table->foreign("teacher_id")->references("user_id")->on("teachers");
            $table->foreign("project_id")->references("id")->on("projects");
            $table->primary(["teacher_id", "project_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_juries');
    }
};
