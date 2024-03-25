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
        Schema::create('news_comments_rating', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('comment_id')->index('fk_news_comment_rating_id')->comment('relates to news comments');
            $table->integer('news_topic_id')->index('fk_news_topic_id');
            $table->integer('user_id')->index('fk_news_user_id');
            $table->integer('agree')->nullable()->comment('relates to news comments');
            $table->integer('disagree')->nullable()->comment('relates to news comments');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_comments_rating');
    }
};
