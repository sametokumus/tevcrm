<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProformaInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proforma_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->string('sale_id');
            $table->text('note')->nullable();
            $table->text('payment_term')->nullable();
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
        Schema::dropIfExists('proforma_invoice_details');
    }
}
