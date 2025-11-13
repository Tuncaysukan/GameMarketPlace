<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wallet_id')->unsigned()->index();
            $table->enum('type', ['credit', 'debit']); // credit: ekleme, debit: çıkarma
            $table->decimal('amount', 18, 4);
            $table->decimal('balance_before', 18, 4);
            $table->decimal('balance_after', 18, 4);
            $table->string('transaction_type'); // order_earning, withdrawal, commission, refund, adjustment
            $table->text('description')->nullable();
            $table->string('reference_type')->nullable(); // Order, Withdrawal, etc.
            $table->integer('reference_id')->unsigned()->nullable();
            $table->json('meta')->nullable(); // Ek bilgiler
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();
            
            // Foreign Key
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            
            // Indexes
            $table->index(['reference_type', 'reference_id']);
            $table->index('transaction_type');
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
        Schema::dropIfExists('wallet_transactions');
    }
}

