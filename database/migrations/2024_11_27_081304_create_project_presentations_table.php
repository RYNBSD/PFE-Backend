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
        Schema::create('project_presentations', function (Blueprint $table) {
            $table->unsignedBigInteger("room_id");
            $table->unsignedBigInteger("project_id");
            $table->timestamp("date");
            $table->foreign("room_id")->references("id")->on("rooms");
            $table->foreign("project_id")->references("id")->on("projects");
            $table->primary(["room_id", "project_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_presentations');
    }
};
