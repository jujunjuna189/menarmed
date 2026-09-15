<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKemampuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kemampuan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('lari')->nullable(true);
            $table->string('renang')->nullable(true);
            $table->string('jatri')->nullable(true); // nembak senjata ringan
            $table->string('jatrat')->nullable(true); // nembak senjata berat
            $table->string('pistol')->nullable(true); // nembak pistol
            $table->string('push_up')->nullable(true);
            $table->string('sit_up')->nullable(true);
            $table->string('pull_up')->nullable(true);
            $table->string('shutle_run')->nullable(true);
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
        Schema::dropIfExists('kemampuan');
    }
}
