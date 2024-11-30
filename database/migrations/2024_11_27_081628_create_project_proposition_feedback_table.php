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
        Schema::create('project_proposition_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("project_proposition_id");
            $table->string("feedback", 255);
            $table->timestamps();
            $table->foreign("project_proposition_id")->references("id")->on("project_propositions");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_proposition_feedback');
    }
};
