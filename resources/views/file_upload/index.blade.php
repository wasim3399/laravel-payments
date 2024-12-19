<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload Large CSV File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
        }
        form {
            margin-bottom: 1rem;
        }
        progress {
            width: 100%;
            height: 20px;
        }
        #status {
            margin-top: 0.5rem;
            font-size: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
<h1>Upload Large CSV File</h1>

<!-- File Upload Form -->
<form id="uploadForm" method="post" action="/upload" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv_file" id="fileInput" required />
    <button type="submit">Upload</button>
</form>

<!-- Progress Bar -->
<progress id="progressBar" value="0" max="100"></progress>
<p id="status">Progress: 0%</p>

<!-- Include the external JavaScript file -->
{{--<script src="{{ asset('upload.js') }}"></script>--}}
</body>
</html>
