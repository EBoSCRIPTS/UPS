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
        Schema::table('news_comments_rating', function (Blueprint $table) {
            $table->foreign(['comment_id'], 'fk_news_comment_rating_id')->references(['id'])->on('news_comments');
            $table->foreign(['user_id'], 'fk_news_user_id')->references(['id'])->on('users');
            $table->foreign(['news_topic_id'], 'fk_news_topic_id')->references(['id'])->on('news_topic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_comments_rating', function (Blueprint $table) {
            $table->dropForeign('fk_news_comment_rating_id');
            $table->dropForeign('fk_news_user_id');
            $table->dropForeign('fk_news_topic_id');
        });
    }
};
