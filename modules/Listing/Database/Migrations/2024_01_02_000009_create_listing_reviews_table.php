<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->integer('order_id')->unsigned()->nullable()->index(); // Sadece satın alan yorumlayabilir
            $table->string('reviewer_name');
            $table->integer('rating'); // 1-5
            $table->text('comment');
            $table->text('vendor_reply')->nullable(); // Satıcı cevabı
            $table->timestamp('vendor_replied_at')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_verified_purchase')->default(false); // Onaylı alışveriş mi
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            
            // Bir kullanıcı bir ilan için sadece bir yorum yapabilir
            $table->unique(['listing_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listing_reviews');
    }
}

