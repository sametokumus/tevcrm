<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_rules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('variation_id')->nullable();
            $table->bigInteger('quantity_stock')->nullable();
            $table->bigInteger('quantity_min')->nullable();
            $table->bigInteger('quantity_step')->nullable();
            $table->tinyInteger('is_free_shipping')->nullable();
            $table->decimal('discounted_rate')->nullable();
            $table->decimal('tax_rate')->nullable();
            $table->decimal('regular_price')->nullable();
            $table->decimal('regular_tax')->nullable();
            $table->decimal('discounted_price')->nullable();
            $table->decimal('discounted_tax')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('product_rules');
    }
}
