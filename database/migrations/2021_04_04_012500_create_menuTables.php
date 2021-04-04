<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id_ro');
            $table->string('label_ro')->unique();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_ro');
            $table->foreign('id_ro')->references('id_ro')->on('roles');
        });
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id_me')->primary();
            $table->string('name_me');
            $table->string('icon_me')->nullable();
            $table->string('slug_me')->nullable();
            $table->uuid('parent_ro')->nullable();
        });
        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('parent_ro')->references('id_me')->on('menus');
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id_me');
            $table->unsignedBigInteger('id_ro');
            $table->boolean('read_pe')->default(false);
            $table->boolean('write_pe')->default(false);
            $table->boolean('overwrite_pe')->default(false);
            $table->unsignedTinyInteger('order_pe')->default(0);
            $table->foreign('id_me')->references('id_me')->on('menus');
            $table->foreign('id_ro')->references('id_ro')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_ro']);
        });
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('menus');
    }
}
