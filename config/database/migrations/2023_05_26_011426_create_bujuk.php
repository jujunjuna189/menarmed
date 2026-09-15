<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBujuk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bujuk', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->longText('deskripsi')->nullable(true);
            $table->longText('path')->comment('URL Bujuk');
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
        Schema::dropIfExists('bujuk');
    }
}
