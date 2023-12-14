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
        <p class="h2">Create new topic</p>
        <form action="{{route('news.create_new_topic')}}" method="POST" id="contentForm">
            @csrf
            <label for="topic">Topic</label>
            <input type="text" name="topic" class="form-control" placeholder="Topic title here.." required>
            <hr class="hr"/>

            <input type="hidden" name="editorContent" id="editorContent">
            <div id="editor">
            </div>

            <button id="submitButton" type="submit" class="btn btn-primary mt-2 float-end">Create new topic</button>
        </form>
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
