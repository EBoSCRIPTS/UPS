<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</head>

<body>
    @if(!isset(Auth::user()->email))
        <script>window.location="/login"</script>
        @endif
    @include('components.sidebar')
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-top: 100px">
            <div class="container" style="width: 80%">
                <p class="h2 text-center">HOME PAGE</p>
            <div class="d-flex flex-wrap justify-content-center">
                <div class="p-2 ml-5 justify-content-center">
                    <a href="/news/view_topic/{{$topics[0]['id']}}">
                        <img src="{{asset($topics[0]['news_image'])}}" class=" rounded d-block mx-auto" alt="..." width="400" height="200">
                        <p class="text-center">{{$topics[0]['topic']}}</p>
                    </a>
                </div>

                <div class="d-flex">
                    <div class="vr"></div>
                </div>

                <div class="p-2 justify-content-center">
                    <a href="/news/view_topic/{{$topics[0]['id']}}">
                    <img src="{{asset($topics[0]['news_image'])}}" class=" rounded d-block mx-auto" alt="..." width="400" height="200">
                        <p class="text-center">{{$topics[0]['topic']}}</p>
                    </a>
                </div>
            </div>
                <hr class="hr"/>
            <div class="p-2 mt-5">
                <div class="row">
                    <a href="/news/view_topic/{{$topics[0]['id']}}" class="col-lg-4">
                        <img src="{{asset($topics[0]['news_image'])}}" class="rounded d-block" alt="..." width="400" height="200">
                        <p class="text-center">{{$topics[0]['topic']}}</p>
                    </a>

                    <a href="/news/view_topic/{{$topics[0]['id']}}" class="col-lg-4">
                        <img src="{{asset($topics[0]['news_image'])}}" class="rounded d-block" alt="..." width="400" height="200">
                        <p class="text-center">{{$topics[0]['topic']}}</p>
                    </a>

                    <a href="/news/view_topic/{{$topics[0]['id']}}" class="col-lg-4">
                        <img src="{{asset($topics[0]['news_image'])}}" class="rounded d-block" alt="..." width="400" height="200">
                        <p class="text-center">{{$topics[0]['topic']}}</p>
                    </a>
                </div>

            </div>
            </div>
        </div>
</body>

