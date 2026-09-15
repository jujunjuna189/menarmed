<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGudangSenjata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gudang_senjata', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('batrai_keluar')->nullable();
            $table->string('batrai_masuk')->nullable();
            $table->dateTime('keluar')->nullable();
            $table->dateTime('masuk')->nullable();
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
        Schema::dropIfExists('gudang_senjata');
    }
}
