<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToProjectStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('project_students', function (Blueprint $table) {
            $table->enum('status', ['Deferred to September', 'Approved for June', 'Pending Approval'])
                  ->default('Pending Approval'); // Set a default value if needed
        });
    }

    public function down()
    {
        Schema::table('project_students', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
