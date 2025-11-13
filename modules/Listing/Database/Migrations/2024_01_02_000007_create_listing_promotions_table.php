<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id')->unsigned()->index();
            $table->integer('vendor_id')->unsigned()->index();
            $table->enum('type', ['boost', 'featured']); // boost: öne çıkarma, featured: vitrin
            $table->integer('duration_days')->default(1); // Süre (gün)
            $table->decimal('price', 18, 4); // Ücret
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->integer('order_id')->unsigned()->nullable(); // Ödeme kaydı
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            
            // Indexes
            $table->index(['type', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listing_promotions');
    }
}

