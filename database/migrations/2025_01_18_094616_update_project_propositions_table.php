<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('project_propositions', function (Blueprint $table) {
        $table->text('validation_feedback')->nullable()->after('status');
    });
}

public function down()
{
    Schema::table('project_propositions', function (Blueprint $table) {
        $table->dropColumn('validation_feedback');
    });
}

};
