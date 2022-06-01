<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_products', function (Blueprint $table) {
            $table->id();
            $table->text('ana_urun_kod');
            $table->text('alt_urun_kod')->nullable();
            $table->text('ana_urun_ad')->nullable();
            $table->text('micro_urun_ad')->nullable();
            $table->text('micro_urun_kod')->nullable();
            $table->text('renk')->nullable();
            $table->text('fiyat')->nullable();
            $table->text('kdv_dahil')->nullable();
            $table->text('para_birimi')->nullable();
            $table->text('yeni_urun')->nullable();
            $table->text('kampanyalı_urun')->nullable();
            $table->text('one_cikan_urun')->nullable();
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
        Schema::dropIfExists('new_products');
    }
}
