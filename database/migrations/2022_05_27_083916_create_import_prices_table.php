<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_prices', function (Blueprint $table) {
            $table->id();
            $table->text('web_servis_kodu')->nullable();
            $table->text('urun_adi')->nullable();
            $table->text('fiyati')->nullable();
            $table->text('indirimli_fiyati')->nullable();
            $table->text('kdv_dahil_fiyati')->nullable();
            $table->text('kdv')->nullable();
            $table->text('alis_fiyatı')->nullable();
            $table->text('yeni_urun_mu')->nullable();
            $table->text('indirimli_goster')->nullable();
            $table->text('tanitimli_goster')->nullable();
            $table->text('sira_no')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_prices');
    }
}
