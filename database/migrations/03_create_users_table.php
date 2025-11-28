
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email', 150)->unique();
            $table->text('avatar_url')->nullable();
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->unsignedDouble('total_recharge', 15, 2)->default(0.00);
            $table->unsignedDouble('balance', 15, 2)->default(0.00);
            $table->unsignedDouble('total_deduct', 15, 2)->default(0.00);
            $table->unsignedDouble('promotion_recharge', 15, 2)->default(0.00);
            $table->unsignedTinyInteger('level')->default(1);
            $table->string('last_ip', 45)->nullable();
            $table->text('last_useragent')->nullable();
            $table->timestamp('last_online')->nullable();
            $table->string('utm_source')->nullable();
            $table->enum('role', ['member', 'admin', 'ctv'])->default('member');
            $table->boolean('status')->default(true);
            $table->string('remember_token', 100)->nullable();
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
        Schema::dropIfExists('users');
    }
};
