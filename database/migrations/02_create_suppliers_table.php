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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->index();
            $table->string('base_url');
            $table->string('api_key', 500);
            $table->string('proxy')->nullable();
            $table->unsignedInteger('price_percent')->default(0);
            $table->unsignedSmallInteger('price_unit_value')->default(1);
            $table->string('api', 25);
            $table->string('username')->default('subold');
            $table->unsignedDouble('money', 15, 2)->default(0.00);
            $table->string('currency', 25)->default('VND');
            $table->unsignedDouble('exchange_rate', 15, 4)->default(1.0000);
            $table->boolean('status')->default(false);
            $table->timestamp('last_synced_at')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
};
