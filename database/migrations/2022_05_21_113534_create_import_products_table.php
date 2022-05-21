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
            $table->text('ana_urun_kod')->nullable();
            $table->text('alt_urun_kod')->nullable();
            $table->text('ana_urun_ad')->nullable();
            $table->text('mikro_urun_ad')->nullable();
            $table->text('mikro_urun_kod')->nullable();
            $table->text('renk')->nullable();
            $table->text('birim')->nullable();
            $table->text('paket_tipi')->nullable();
            $table->text('kmk')->nullable();
            $table->text('marka')->nullable();
            $table->text('arama_kelimeleri')->nullable();
            $table->text('aciklama')->nullable();
            $table->text('kisa_aciklama')->nullable();
            $table->text('notlar')->nullable();
            $table->text('agirlik')->nullable();
            $table->text('seo_baslik')->nullable();
            $table->text('seo_kelimeler')->nullable();
            $table->text('alt_urun_var')->nullable();
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
