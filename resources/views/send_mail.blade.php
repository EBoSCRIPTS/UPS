<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Send Mail</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
       <p class="h2">Send email to all users</p>
        <form action="{{route('send_mail')}}" method="POST" enctype="multipart/form-data">
           @csrf
            <label for="subject">Subject</label>
           <input type="text" name="subject" class="form-control">

           <label for="message">Message</label>
           <textarea name="message" class="form-control" rows="5"></textarea>

           <label for="attachments">Attachments</label>
           <input type="file" name="attachments" class="form-control">

           <button type="submit" class="btn btn-primary mt-2 float-end">Send Mail</button>
       </form>

    </div>
</div>
</body>
