<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.tiny.cloud/1/ccay5jwlbpn4z04asndkp5uh7ncp5gsl00lqbsksn26ky7o3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <p class="h2">Create new topic</p>
        <form>
            @csrf
            <input type="text" name="topic" id="topic" class="form-control" placeholder="Title here.." required>
            <textarea name="content" id="content"></textarea>
            <button type="submit" class="btn btn-primary mt-2 float-end">Create a new topic</button>
        </form>
    </div>

</div>
</body>

<script>
    tinymce.init({
        selector: '#content'
    });
</script>
