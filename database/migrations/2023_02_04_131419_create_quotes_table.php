<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_id');
            $table->string('sale_id');
            $table->string('payment_term')->nullable();
            $table->decimal('advance_price',10,2, false)->default(0);
            $table->string('lead_time')->nullable();
            $table->string('delivery_term')->nullable();
            $table->string('country_of_destination')->nullable();
            $table->string('note')->nullable();
            $table->date('expiry_date')->nullable();
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
        Schema::dropIfExists('quotes');
    }
}
