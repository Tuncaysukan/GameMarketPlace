<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->unsigned()->index();
            $table->integer('wallet_id')->unsigned()->index();
            $table->decimal('amount', 18, 4);
            $table->string('method'); // bank_transfer, paypal, etc.
            $table->json('payment_details'); // Banka bilgileri, PayPal email vb.
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected', 'cancelled'])->default('pending');
            $table->text('note')->nullable();
            $table->text('admin_note')->nullable();
            $table->integer('processed_by')->unsigned()->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
}

