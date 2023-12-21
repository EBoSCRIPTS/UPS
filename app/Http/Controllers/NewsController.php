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
        $topics = NewsTopicModel::all();
        return view('news_creation', ['topics' => $topics]);
    }

    public function insertNewTopic(Request $request)
    {
        if ($request->hasFile('coverPhoto')) {
            $image = $request->file('coverPhoto');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $imageName = public_path('uploads/'.$imageName);

            $uploadFolder = 'uploads/';
            $imageName = baseName($imageName);
            $imageName = $uploadFolder.$imageName;
        }

        $newTopic = new NewsTopicModel([
            'topic' => $request->input('topic'),
            'text' => $request->input('editorContent'),
            'about' => $request->input('about'),
            'news_image' => $imageName,
        ]);

        $newTopic->save();

        return back()->with('success', 'News topic created successfully!');
    }

    //used for loading in specific topic
    public function loadNewsTopic(Request $request)
    {
        $topic = NewsTopicModel::query()->where('id', $request->topic_id)->get()->toArray();
        $comments = NewsCommentsModel::query()->where('topic_id', $request->topic_id)->get();

        foreach($comments as $comment){
            $comment->agree = NewsCommentsRatingModel::query()->where('comment_id', $comment->id)->where('agree', 1)->count();
            $comment->disagree = NewsCommentsRatingModel::query()->where('comment_id', $comment->id)->where('disagree', 1)->count();
        }

        return view('news_topic_view',['topic' => $topic, 'comments' => $comments]);
    }

    //this is only used for landing page(home page)
    public function loadAllTopics()
    {
        return view('home', ['topics' => NewsTopicModel::query()->orderBy('id', 'desc')->limit(6)->get()->toArray()]);
    }

    public function loadNewsPageTopics()
    {
        return view('news_all_news', ['topics' => NewsTopicModel::query()->orderBy('id', 'desc')->get()]);
    }

    public function loadEditNewsTopic(Request $request)
    {
        $topic = NewsTopicModel::query()->where('id', $request->topic_id)->first();
        return view('news_edit', ['topic' => $topic]);
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

    public function updateTopic(Request $request)
    {
        $topic = NewsTopicModel::query()->where('id', $request->input('topic_id'))->first();

        if ($request->hasFile('coverImage')) {
            $image = $request->file('coverImage');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $imageName = public_path('uploads/'.$imageName);

            $uploadFolder = 'uploads/';
            $imageName = baseName($imageName);
            $imageName = $uploadFolder.$imageName;
        }

        $topic->update([
           'topic' => $request->input('topic') ?? $topic->topic,
           'text' => $request->input('editorContent') ?? $topic->text,
           'about' => $request->input('about') ?? $topic->about,
           'news_image' => $imageName ?? $topic->news_image,
        ]);

        return back()->with('success', 'News topic updated successfully!');
    }

    public function deleteNewsTopic(Request $request)
    {
        $topic = NewsTopicModel::query()->where('id', $request->topic_id)->first();

        $topicComments = NewsCommentsModel::query()->where('topic_id', $request->topic_id)->get();

        foreach($topicComments as $topicComment){
            $topicComment->delete();
        }

        $topic->delete();

        return back()->with('success', 'News topic deleted successfully!');
    }

    public function rateTopicComment(Request $request)
    {
        if (NewsCommentsRatingModel::query()->where('news_topic_id', $request->topic_id)
            ->where('comment_id', $request->comment_id)
            ->where('user_id', auth()->user()->id)
            ->exists()) {
            return back()->with('error', 'Comment already rated!');
        }

        //check what we get in GET request
        if ($request->uprate != 'downrate'){
            $rate = new NewsCommentsRatingModel([
                'comment_id' => $request->comment_id,
                'news_topic_id' => $request->topic_id,
                'user_id' => auth()->user()->id,
                'agree' => 1,
            ]);
            $rate->save();

            return back()->with('success', 'Comment rated successfully!');
        }
        else{
            $rate = new NewsCommentsRatingModel([
                'comment_id' => $request->comment_id,
                'news_topic_id' => $request->topic_id,
                'user_id' => auth()->user()->id,
                'disagree' => 1,
            ]);
            $rate->save();
            return back()->with('success', 'Comment rated successfully!');
        }

    }
}
