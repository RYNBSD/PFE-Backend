<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\EmailStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("admin_id");
            $table->string("subject", 255);
            $table->text("content");
            $table->unsignedBigInteger("receiver_id");
            $table->enum("status", EmailStatus::values());
            $table->foreign("admin_id")->references("user_id")->on("admins");
            $table->foreign("receiver_id")->references("id")->on("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
