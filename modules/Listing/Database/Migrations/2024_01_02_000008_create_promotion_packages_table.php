<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // Paket adı
            $table->enum('type', ['boost', 'featured']);
            $table->integer('duration_days'); // Süre (gün)
            $table->decimal('price', 18, 4); // Fiyat
            $table->text('description')->nullable();
            $table->integer('position')->default(0); // Sıralama
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('promotion_packages');
    }
}

