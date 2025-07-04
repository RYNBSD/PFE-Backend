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
        Schema::create('project_registrations', function (Blueprint $table) {
            $table->unsignedBigInteger("project_id");
            $table->timestamp("start_date");
            $table->timestamp("end_date");
            $table->foreign("project_id")->references("id")->on("projects");
            $table->primary("project_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_registrations');
    }
};
