<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_security', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->unsignedTinyInteger('attempt_login')->default(0);
            $table->unsignedTinyInteger('attempt_error')->default(0);
            $table->string('banned_reason')->nullable();
            $table->boolean('twofa_enabled')->default(false);
            $table->string('twofa_secret', 100);
            $table->text('twofa_qr');
            $table->boolean('otp_email_enabled')->default(false);
            $table->string('otp_email_code', 6)->nullable();
            $table->timestamp('otp_email_expires')->nullable();
            $table->string('api_token', 100);
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_security');
    }
};
