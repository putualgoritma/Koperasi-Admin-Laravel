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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger("parent_id")->nullable();
            $table->string('code', 20);
            $table->string('name', 100);
            $table->enum('type', ['Assets', 'Liabilities', 'Equity', 'Revenues', 'Expenses'])->default('Assets');
            $table->enum('postable', ['Y', 'N'])->default('Y');
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
        Schema::dropIfExists('accounts');
    }
};
