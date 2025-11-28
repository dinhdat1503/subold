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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('action_type', 50)->index();
            $table->unsignedDouble('old_value', 15, 2)->default(0.00);
            $table->double('value', 15, 2)->default(0.00);
            $table->unsignedDouble('new_value', 15, 2)->default(0.00);
            $table->string('ip_address', 45)->nullable();
            $table->text('useragent')->nullable();
            $table->text('description');
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
        Schema::dropIfExists('logs');
    }
};
