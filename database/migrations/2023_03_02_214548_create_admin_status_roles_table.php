<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminStatusRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_status_roles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('admin_role_id');
            $table->bigInteger('status_id');
            $table->tinyInteger('read')->default(1);
            $table->tinyInteger('write')->default(1);
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
        Schema::dropIfExists('admin_status_roles');
    }
}
