<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_products', function (Blueprint $table) {
            $table->id();
            $table->string('offer_id');
            $table->bigInteger('request_product_id');
            $table->integer('quantity');
            $table->integer('measurement_id')->default(0);
            $table->decimal('pcs_price',10,2, false)->nullable();
            $table->decimal('total_price',10,2, false)->nullable();
            $table->decimal('discount_rate',10,2, false)->nullable();
            $table->decimal('discounted_price',10,2, false)->nullable();
            $table->decimal('vat_rate',10,2, false)->nullable();
            $table->string('package_type')->nullable();
            $table->string('date_code')->nullable();
            $table->text('comment')->nullable();
            $table->string('currency')->nullable();
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
        Schema::dropIfExists('offer_products');
    }
}
