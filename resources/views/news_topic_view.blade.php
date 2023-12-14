<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>


<body>
<div class="row">
@include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
       <p class="h1">{{$topic[0]['topic']}}</p>

        <div id="textContent">
            {!! $topic[0]['text'] !!}
        </div>

        <hr class="hr"/>
        <p class="h3">Comments</p>
        @if(isset($comments))
            @foreach($comments as $comment)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{$comment['name']}}</h5>
                        <p class="card-text">{{$comment['comment']}}</p>
                    </div>
                </div>
            @endforeach
        @endif
        <form action="{{route('news.add_comment')}}" method="POST">
            @csrf
            <input type="hidden" name="topic_id" value="{{$topic[0]['id']}}">
            <label for="name">Your name</label>
            <input type="text" name="name" class="form-control" placeholder="Your name..">
            <textarea type="text" name="text" class="form-control" style="min-height: 100px"> </textarea>
            <button type="submit" class="btn btn-primary btn-sm mt-3 float-end">Add comment</button>
        </form>
    </div>
</div>
</body>

<script>

</script>
