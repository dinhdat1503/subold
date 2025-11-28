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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('server_id')
                ->nullable()
                ->constrained('server_services')
                ->onDelete('set null');
            $table->unsignedDouble('quantity', 15, 2);
            $table->unsignedDouble('payment', 15, 2);
            $table->unsignedDouble('payment_real', 15, 2);
            $table->double('profit', 15, 2)->default(0);
            $table->string('order_link')->index();
            $table->text('order_info')->nullable();
            $table->unsignedInteger('count_start')->default(0);
            $table->unsignedInteger('count_buff')->default(0);
            $table->timestamp('time_start')->nullable();
            $table->timestamp('time_end')->nullable();
            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('suppliers')
                ->onDelete('set null');
            $table->string('supplier_order_id', 50)->index();
            $table->string('status', 25)->default('Pending')->index();
            $table->text('logs');
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
        Schema::dropIfExists('orders');
    }
};
