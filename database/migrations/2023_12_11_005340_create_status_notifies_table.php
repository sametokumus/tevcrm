<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_notifies', function (Blueprint $table) {
            $table->id();
            $table->string('notify_id');
            $table->bigInteger('setting_id');
            $table->string('sale_id')->nullable();
            $table->bigInteger('sender_id')->nullable();
            $table->bigInteger('receiver_id')->nullable();
            $table->string('notify')->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->tinyInteger('type')->default(1); //1:notification, 2:mail
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
        Schema::dropIfExists('status_notifies');
    }
}
