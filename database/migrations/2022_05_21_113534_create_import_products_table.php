<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_products', function (Blueprint $table) {
            $table->id();
            $table->text('ana_ürün_kod')->nullable();
            $table->text('alt_ürün_kod')->nullable();
            $table->text('ana_ürün_ad')->nullable();
            $table->text('mikro_ürün_ad')->nullable();
            $table->text('mikro_ürün_kod')->nullable();
            $table->text('renk')->nullable();
            $table->text('birim')->nullable();
            $table->text('paket_tipi')->nullable();
            $table->text('kmk')->nullable();
            $table->text('marka')->nullable();
            $table->text('arama_kelimeleri')->nullable();
            $table->text('açıklama')->nullable();
            $table->text('kısa_açıklama')->nullable();
            $table->text('notlar')->nullable();
            $table->text('ağırlık')->nullable();
            $table->text('seo_başlık')->nullable();
            $table->text('seo_kelimeler')->nullable();
            $table->text('alt_ürün_var')->nullable();
            $table->text('resim')->nullable();
            $table->text('cins')->nullable();
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
        Schema::dropIfExists('import_products');
    }
}
