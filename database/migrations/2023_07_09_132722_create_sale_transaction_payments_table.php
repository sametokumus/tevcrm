<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTransactionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_transaction_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id');
            $table->string('transaction_id');
            $table->bigInteger('payment_term')->default(1);
            $table->tinyInteger('payment_type')->default(0); //1: Peşin, 2: Vadeli
            $table->tinyInteger('payment_method')->default(0); //1: Nakit, 2: Kredi Kartı, 3: Çek
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->tinyInteger('payment_status_id')->default(1);
            $table->integer('payment_tax_rate')->default(0);
            $table->decimal('payment_price',10,2, false)->nullable();
            $table->decimal('payment_tax',10,2, false)->nullable();
            $table->decimal('payment_total',10,2, false)->nullable();
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
        Schema::dropIfExists('sale_transaction_payments');
    }
}
