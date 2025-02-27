<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pooling Number</title>
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .card {
            width: 400px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="card">
        <h4 class="mb-4">Hello {{$name}}!</h4>
        <p class="lead">Your pooling number is <strong>{{$code}}</strong>.</p>
        <div class="alert alert-info" role="alert">
            Remember this specific identification number.
        </div>
    </div>
</body>
</html>
