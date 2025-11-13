<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->unsigned()->index();
            $table->integer('category_id')->unsigned()->nullable()->index();
            
            // Temel Bilgiler
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 18, 4);
            $table->string('sku')->nullable()->unique();
            
            // Teslimat Tipi
            $table->enum('delivery_type', ['automatic', 'manual'])->default('manual');
            
            // Stok Yönetimi (Otomatik Teslimat için)
            $table->boolean('manage_stock')->default(false);
            $table->integer('stock_qty')->default(0);
            $table->boolean('in_stock')->default(true);
            
            // Manuel Teslimat Bilgisi
            $table->text('manual_delivery_note')->nullable();
            $table->integer('processing_days')->default(3); // İşlem süresi (gün)
            
            // Durum ve Onay
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'inactive'])->default('draft');
            $table->boolean('is_active')->default(false);
            $table->text('rejection_reason')->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
            
            // Boost / Vitrin Özellikleri
            $table->boolean('is_featured')->default(false); // Vitrin
            $table->boolean('is_boosted')->default(false); // Boost
            $table->timestamp('boost_expires_at')->nullable();
            $table->timestamp('featured_expires_at')->nullable();
            
            // Görüntülenme ve İstatistikler
            $table->integer('view_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('total_sales', 18, 4)->default(0);
            $table->decimal('rating', 3, 2)->default(0); // 0.00 - 5.00
            $table->integer('rating_count')->default(0);
            
            // SEO
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Özellikler (JSON)
            $table->json('custom_fields')->nullable();
            
            // Zamanlar
            $table->softDeletes();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }
}

