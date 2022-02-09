<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogArticleMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_article_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('article_message_id');
            $table->boolean('is_admin')->commit('是否為管理員操作');
            $table->string('ip');
            $table->string('type');
            $table->json('previous_data');
            $table->json('current_data');
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
        Schema::dropIfExists('log_article_messages');
    }
}
