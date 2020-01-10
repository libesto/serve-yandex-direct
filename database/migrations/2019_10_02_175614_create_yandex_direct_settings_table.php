<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYandexDirectSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yandex_direct_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
            $table->string('token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->bigInteger('expires_in')->nullable();
            $table->string('login')->nullable();
            
            $table->longText('campaigns')->nullable();
            
            $table->longText('goods_check')->nullable();
    
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
        Schema::dropIfExists('yandex_direct_settings');
    }
}
