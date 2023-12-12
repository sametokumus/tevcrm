<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusNotifySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_notify_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('status_id');
            $table->bigInteger('role_id')->nullable();
            $table->json('receivers')->nullable();
            $table->tinyInteger('is_notification')->default(0);
            $table->tinyInteger('is_mail')->default(0);
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
        Schema::dropIfExists('status_notify_settings');
    }
}
