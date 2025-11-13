<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMobileAndLogoFileIdToSliderSlideTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slider_slide_translations', function (Blueprint $table) {
            $table->integer('mobile_file_id')->after('file_id')->nullable()->unsigned();
            $table->integer('logo_file_id')->after('mobile_file_id')->nullable()->unsigned();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slider_slide_translations', function (Blueprint $table) {
            $table->dropColumn(['mobile_file_id', 'logo_file_id']);
        });
    }
}