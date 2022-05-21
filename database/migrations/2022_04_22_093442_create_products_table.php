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
            $table->bigInteger('brand_id');
            $table->bigInteger('type_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('notes')->nullable();
            $table->string('sku');
            $table->decimal('delivery_price')->nullable();
            $table->decimal('delivery_tax')->nullable();
            $table->tinyInteger('is_free_shipping')->default(0);
            $table->tinyInteger('view_all_images')->default(0);
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
