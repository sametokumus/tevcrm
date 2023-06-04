<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('brand_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->string('ref_code')->nullable();
            $table->string('product_name')->nullable();
            $table->string('stock_code')->nullable();
            $table->string('date_code')->nullable();
            $table->bigInteger('stock_quantity')->default(0);
            $table->decimal('price',10,2, false)->nullable();
            $table->string('currency')->nullable();
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
        Schema::dropIfExists('products');
    }
}
