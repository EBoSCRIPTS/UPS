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
        if ($request->hasFile('coverPhoto')) {
            $image = $request->file('coverPhoto');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);

            $imageName = public_path('uploads/'.$imageName);
            dd($imageName);
        }

        $newTopic = new NewsTopicModel([
            'topic' => $request->input('topic'),
            'text' => $request->input('editorContent'),
            'news_image' => $imageName,
        ]);

        $newTopic->save();

        return back()->with('success', 'News topic created successfully!');
    }

    public function loadNewsTopic(Request $request)
    {
        $topic = NewsTopicModel::query()->where('id', $request->topic_id)->get()->toArray();
        $comments = NewsCommentsModel::query()->where('topic_id', $request->topic_id)->get();

        return view('news_topic_view',['topic' => $topic, 'comments' => $comments]);
    }

    public function postTopicComment(Request $request)
    {
        $comments = new NewsCommentsModel([
            'topic_id' => $request->input('topic_id'),
            'comment' => $request->input('text'),
            'name' => $request->input('name'),
        ]);

        $comments->save();

        return back()->with('success', 'Comment created successfully!');
    }

    public function loadAllTopics()
    {
        return view('home', ['topics' => NewsTopicModel::query()->orderBy('id', 'desc')->get()->toArray()]);
    }
}
