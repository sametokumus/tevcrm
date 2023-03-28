<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferRequestProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_request_products', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('product_id');
            $table->integer('quantity')->nullable();
            $table->integer('measurement_id')->default(0);
            $table->string('customer_stock_code')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('offer_request_products');
    }
}
