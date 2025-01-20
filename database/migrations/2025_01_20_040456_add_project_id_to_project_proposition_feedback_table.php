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
        Schema::table('project_proposition_feedback', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id'); // Add the new column
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade'); // Add foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_proposition_feedback', function (Blueprint $table) {
            $table->dropForeign(['project_id']); // Drop the foreign key
            $table->dropColumn('project_id'); // Drop the column
        });
    }
};
