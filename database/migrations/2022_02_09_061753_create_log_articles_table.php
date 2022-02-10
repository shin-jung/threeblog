<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('article_id');
            $table->boolean('is_admin')->commit('是否為管理員操作');
            $table->string('ip');
            $table->string('type');
            $table->string('previous_message');
            $table->string('current_message');
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
        Schema::dropIfExists('log_articles');
    }
}
