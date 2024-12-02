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
        Schema::create('project_submits', function (Blueprint $table) {
            $table->unsignedBigInteger("project_id");
            $table->unsignedBigInteger("group_id");
            $table->boolean("validated")->default(false);
            $table->foreign("project_id")->references("id")->on("projects");
            $table->foreign("group_id")->references("group_id")->on("groups");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_submits');
    }
};
