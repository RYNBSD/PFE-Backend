<?php

use App\Enums\ProjectPropositionsStatus;
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
        Schema::create('project_propositions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("project_id")->unique();
            $table->enum("status", ProjectPropositionsStatus::values());
            $table->unsignedBigInteger("validated_by");
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("project_id")->references("id")->on("projects");
            $table->foreign("validated_by")->references("user_id")->on("admins");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_propositions');
    }
};
