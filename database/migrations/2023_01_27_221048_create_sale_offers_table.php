<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_offers', function (Blueprint $table) {
            $table->id();
            $table->string('sale_id');
            $table->string('offer_id');
            $table->bigInteger('offer_product_id')->default(0);
            $table->bigInteger('product_id')->default(0);
            $table->bigInteger('supplier_id')->default(0);
            $table->string('date_code');
            $table->string('package_type');
            $table->bigInteger('request_quantity');
            $table->bigInteger('offer_quantity');
            $table->decimal('pcs_price',10,2, false)->nullable();
            $table->decimal('total_price',10,2, false)->nullable();
            $table->decimal('discount_rate',10,2, false)->nullable();
            $table->decimal('discounted_price',10,2, false)->nullable();
            $table->decimal('vat_rate',10,2, false)->nullable();
            $table->string('currency')->nullable();
            $table->decimal('offer_price',10,2, false)->nullable();
            $table->string('offer_currency')->nullable();
            $table->decimal('supply_price',10,2, false)->nullable();
            $table->string('supply_currency')->nullable();
            $table->decimal('sale_price',10,2, false)->nullable();
            $table->string('sale_currency')->nullable();
            $table->integer('lead_time')->nullable();
            $table->tinyInteger('active')->default(1);
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
        Schema::dropIfExists('sale_offers');
    }
}
