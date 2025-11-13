<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_stock_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id')->unsigned()->index();
            $table->text('stock_data'); // Ürün bilgisi (kod, key, hesap bilgisi vb.)
            $table->enum('status', ['available', 'sold', 'reserved'])->default('available');
            $table->integer('order_id')->unsigned()->nullable()->index();
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listing_stock_items');
    }
}

