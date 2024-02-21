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
        Schema::create('ledger_account', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['D', 'C'])->default('D');
            $table->decimal('amount', $precision = 12, $scale = 2);
            $table->timestamps();
            $table->foreignId('ledger_id')
                ->constrained("ledgers")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('account_id')
                ->constrained("accounts")
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledger_account');
    }
};
