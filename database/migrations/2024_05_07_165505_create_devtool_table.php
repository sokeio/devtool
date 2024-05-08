<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dev_crud', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('module')->nullable();
            $table->string('name')->nullable();
            $table->string('model')->nullable();
            $table->string('model_name')->nullable();
            $table->string('route')->nullable();
            $table->string('table_name')->nullable();
            $table->string('table_title')->nullable();
            $table->json('fields')->nullable();
            $table->json('form')->nullable();
            $table->json('table')->nullable();
            $table->json('config')->nullable();
            $table->timestamps();
        });
        Schema::create('dev_crud_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dev_crud_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('module')->nullable();
            $table->string('name')->nullable();
            $table->string('model')->nullable();
            $table->string('route')->nullable();
            $table->string('model_name')->nullable();
            $table->string('table_name')->nullable();
            $table->string('table_title')->nullable();
            $table->json('fields')->nullable();
            $table->json('form')->nullable();
            $table->json('table')->nullable();
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dev_crud');
        Schema::dropIfExists('dev_crud_logs');
    }
};
