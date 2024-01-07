<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>News</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            <p class="h2 text-center">All news</p>


            @foreach($topics as $topic)
                <a href="/news/view_topic/{{$topic->id}}" style="text-decoration: none">
                    <div class="d-flex flex-wrap justify-content-center">
                        <div class="card mb-3 bg-light">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{asset($topic->news_image)}}" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{$topic->topic}}</h5>
                                        <p class="card-text">{{$topic->about}}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </a>
            @endforeach
        </div>

    </div>
</body>
