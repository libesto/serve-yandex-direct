<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYandexDirectRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yandex_direct_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
    
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
            $table->boolean('daily_run')->default(false);
            $table->timestamp('next_run')->nullable();
            $table->boolean('running')->default(false);
    
            $table->index(['daily_run', 'next_run', 'running']);
            
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
        Schema::dropIfExists('yandex_direct_runs');
    }
}
