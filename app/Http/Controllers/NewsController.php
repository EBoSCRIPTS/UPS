<?php

namespace App\Http\Controllers;

use App\Models\News\NewsCommentsModel;
use App\Models\News\NewsCommentsRatingModel;
use App\Models\News\NewsTopicModel;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function createTopic()
    {
        return view('news_creation');
    }

    public function insertNewTopic(Request $request)
    {
        $newTopic = new NewsTopicModel([
            'topic' => $request->input('topic'),
            'text' => $request->input('editorContent'),
            'news_image' => $request->input('image') ?? null,
        ]);

        $newTopic->save();

        return back()->with('success', 'News topic created successfully!');
    }

    public function loadNewsTopic(Request $request)
    {
        $topic = NewsTopicModel::query()->where('id', $request->topic_id)->get()->toArray();
        $comments = NewsCommentsModel::query()->where('topic_id', $request->topic_id)->get()->toArray();

        return view('news_topic_view',['topic' => $topic, 'comments' => $comments]);
    }

    public function postTopicComment(Request $request)
    {
        $comments = new NewsCommentsModel([
            'topic_id' => $request->input('topic_id'),
            'comment' => $request->input('text'),
            'name' => $request->input('name')
        ]);

        $comments->save();

        return back()->with('success', 'Comment created successfully!');
    }
}
