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
        Schema::create('capitals', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('user_id')
                ->constrained("users")
                ->onUpdate('cascade')
                ->onDelete('cascade'); 
            $table->unsignedBigInteger("ledger_id")->nullable();  
            $table->tinyText('memo');
            $table->enum('type', ['D', 'C'])->default('D');
            $table->decimal('amount', $precision = 12, $scale = 2);
            $table->date('register')->nullable();
            $table->string('period', 10)->nullable();
            $table->enum('status', ['pending', 'active', 'close'])->default('pending');
            $table->string('model', 10);
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
        Schema::dropIfExists('capitals');
    }
};
