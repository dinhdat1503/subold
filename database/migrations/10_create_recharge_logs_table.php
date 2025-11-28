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
        Schema::create('recharge_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('recharge_id')
                ->nullable()
                ->constrained('recharge_methods')
                ->onDelete('set null');
            $table->string('trans_id')->index();
            $table->unsignedDouble('amount', 15, 2)->default(0.00);
            $table->unsignedDouble('promotion', 15, 2)->default(0.00);
            $table->unsignedDouble('amount_received', 15, 2)->default(0.00);
            $table->unsignedTinyInteger('status')->default(0);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('recharge_logs');
    }
};
