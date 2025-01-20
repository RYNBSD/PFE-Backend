<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('projects', function (Blueprint $table) {
        // Allow NULL first, then backfill data and make it NOT NULL if needed.
        $table->text('technologies')->nullable()->after('status');
        $table->text('material_needs')->nullable()->after('technologies');
        $table->enum('master_option', ['GL', 'IA', 'SIC', 'RSD'])->nullable()->after('material_needs');
    });

    // Backfill columns with default values if necessary
    DB::table('projects')->update([
        'technologies' => 'Not Specified',
        'material_needs' => 'Not Specified',
        'master_option' => 'GL', // Default to a valid value from the enum
    ]);

    Schema::table('projects', function (Blueprint $table) {
        // Modify columns to NOT NULL after backfilling
        $table->text('technologies')->nullable(false)->change();
        $table->text('material_needs')->nullable(false)->change();
        $table->enum('master_option', ['GL', 'IA', 'SIC', 'RSD'])->nullable(false)->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn(['technologies', 'material_needs', 'master_option']);
    });
}
};
