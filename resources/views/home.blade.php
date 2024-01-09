<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HOME</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

</head>

<body>
<div class="row">
    @if(!isset(Auth::user()->email))
        <script>window.location = "/login"</script>
    @endif
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            <p class="h4 text-center"> NEWS </p>
            <div class="d-flex flex-wrap justify-content-center">
                <div class="p-2 ml-5 justify-content-center">
                    <a href="/news/view_topic/{{$topics[0]['id'] ?? '/'}}">
                        <img src="{{asset($topics[0]['news_image'] ?? 'PLACEHOLDER')}}" class=" rounded d-block img-fluid" alt="..."
                             width="400" height="200">
                        <p class="text-center">{{$topics[0]['topic'] ?? 'PLACEHOLDER'}}</p>
                    </a>
                </div>

                <div class="d-flex">
                    <div class="vr"></div>
                </div>

                <div class="p-2 justify-content-center">
                    <a href="/news/view_topic/{{$topics[1]['id'] ?? '/'}}">
                        <img src="{{asset($topics[1]['news_image'] ?? 'PLACEHOLDER')}}" class=" rounded d-block img-fluid" alt="..."
                             width="400" height="200">
                        <p class="text-center">{{$topics[1]['topic'] ?? 'PLACEHOLDER'}}</p>
                    </a>
                </div>
            </div>
            <hr class="hr"/>
            <div class="p-2 mt-5">
                <div class="row">
                    <a href="/news/view_topic/{{$topics[2]['id'] ?? '/'}}" class="col-lg-4">
                        <img src="{{asset($topics[2]['news_image'] ?? 'PLACEHOLDER')}}" class="rounded d-block img-fluid" alt="..."
                             width="400" height="200">
                        <p class="text-center">{{$topics[2]['topic'] ?? 'PLACEHOLDER'}}</p>
                    </a>

                    <a href="/news/view_topic/{{$topics[3]['id'] ?? '/'}}" class="col-lg-4">
                        <img src="{{asset($topics[3]['news_image'] ?? 'PLACEHOLDER')}}" class="rounded d-block img-fluid" alt="..."
                             width="400" height="200">
                        <p class="text-center">{{$topics[3]['topic'] ?? 'PLACEHOLDER'}}</p>
                    </a>

                    <a href="/news/view_topic/{{$topics[4]['id'] ?? '/'}}" class="col-lg-4">
                        <img src="{{asset($topics[4]['news_image'] ?? 'PLACEHOLDER')}}" class="rounded d-block img-fluid" alt="..."
                             width="400" height="200">
                        <p class="text-center">{{$topics[4]['topic'] ?? 'PLACEHOLDER'}}</p>
                    </a>
                </div>

            </div>
            <p class="h4 text-center"><a href="/news/topics">View all topics </a></p>
        </div>
    </div>
</body>

