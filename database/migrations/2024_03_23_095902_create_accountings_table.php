<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accountings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('offer_id');
            $table->decimal('test_total',10,2, false)->nullable();
            $table->decimal('discount',10,2, false)->nullable();
            $table->decimal('sub_total',10,2, false)->nullable();
            $table->decimal('vat',10,2, false)->nullable();
            $table->decimal('vat_rate',10,2, false)->nullable();
            $table->decimal('grand_total',10,2, false)->nullable();
            $table->bigInteger('status_id')->default(1);
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
        Schema::dropIfExists('accountings');
    }
}
