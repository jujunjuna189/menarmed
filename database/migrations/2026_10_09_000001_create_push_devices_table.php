<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('push_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('installation_id', 64)->unique();
            $table->text('token');
            $table->string('token_hash', 64)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('push_devices');
    }
}
