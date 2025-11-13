<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->unsigned()->unique()->index();
            $table->decimal('balance', 18, 4)->default(0); // Mevcut bakiye
            $table->decimal('pending_balance', 18, 4)->default(0); // Bekleyen bakiye
            $table->decimal('total_earned', 18, 4)->default(0); // Toplam kazanç
            $table->decimal('total_withdrawn', 18, 4)->default(0); // Toplam çekim
            $table->decimal('total_commission', 18, 4)->default(0); // Toplam komisyon
            $table->string('currency')->default('TRY');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign Key
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}

