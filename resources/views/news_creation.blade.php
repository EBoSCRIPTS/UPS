<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            <p class="h2">Create new topic</p>
            <form action="{{route('news.create_new_topic')}}" method="POST" id="contentForm" enctype="multipart/form-data">
                @csrf
                <label for="topic">Topic</label>
                <input type="text" name="topic" class="form-control" placeholder="Topic title here.." required>
                <hr class="hr"/>

                <input type="hidden" name="editorContent" id="editorContent">
                <div id="editor" style="min-height: 300px">
                </div>

                <label for="about">About</label>
                <input type="text" name="about" class="form-control" placeholder="Quick summary here.." required>
                <hr class="hr"/>

                <label for="coverPhoto">Cover Image</label>
                <input type="file" name="coverPhoto" class="form-control">
                <button id="submitButton" type="submit" class="btn btn-primary mt-2 float-end">Create new topic</button>
            </form>
            <hr>
            <p class="h2">Edit Topics</p>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Topic name</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($topics as $topic)
                    <tr>
                        <td>{{$topic->topic}}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('news.edit_topic', ['topic_id' => $topic->id])}}" class="btn btn-primary">Edit</a>
                                <form action="{{route('news.delete_topic', ['topic_id' => $topic->id])}}" method="POST">
                                    @csrf
                                <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
        </div>
    </div>

</div>
</body>

<script>
    const toolBar = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],

        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction

        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean']
    ];

    let quill = new Quill('#editor', {
        modules: {
            toolbar: toolBar
        },
        theme: 'snow',
    });

    let userContent = document.getElementsByClassName('ql-editor');
    const submitButton = document.getElementById('submitButton');
    const form = document.getElementById('contentForm');
    const inputField = document.getElementById('editorContent');


    form.onsubmit = function()
    {
        pageContent = document.querySelector('.ql-editor').innerHTML;
        inputField.value = pageContent;

        return true;
    }
</script>
