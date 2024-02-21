<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_module', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('module', 20);
            $table->foreignId('account_id')
                ->constrained("accounts")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('type', ['D', 'C'])->default('D');
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
        Schema::dropIfExists('account_module');
    }
};
