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
        Schema::create('server_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->onDelete('set null');
            $table->unsignedInteger('server')->nullable();
            $table->string('flag', 5)->nullable();
            $table->unsignedDouble('price', 15, 2);
            $table->unsignedInteger('min');
            $table->unsignedInteger('max');
            $table->text('title');
            $table->text('description')->nullable();
            $table->boolean('status')->default(false);
            $table->string('action_reaction')->default(json_encode(['status' => false]));
            $table->string('action_time')->default(json_encode(['status' => false]));
            $table->string('action_comment')->default(json_encode(['status' => false]));
            $table->string('action_amount')->default(json_encode(['status' => false]));
            $table->string('action_order')->default(json_encode(['multi_link' => false, 'refund' => false, 'warranty' => false]));
            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->onDelete('cascade');
            $table->string('supplier_code', 50);

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
        Schema::dropIfExists('server_services');
    }
};
