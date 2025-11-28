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
        Schema::create('recharge_methods', function (Blueprint $table) {
            $table->id();
            $table->enum('method_type', ['bank', 'crypto']);
            $table->string('name', 25);
            $table->double('exchange_rate', 15, 4)->default(1.0000);
            $table->unsignedInteger('recharge_min')->default(10000);
            $table->string('account_name', 100)->nullable();
            $table->string('account_index', 100);
            $table->string('wallet_qr', 100)->nullable();
            $table->string('network', 25)->nullable();
            $table->string('api_key', 500)->nullable();
            $table->text('note');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recharge_methods');
    }
};
