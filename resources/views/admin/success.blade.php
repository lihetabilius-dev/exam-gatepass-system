<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Success</title>
   
    <meta http-equiv="refresh" content="3;url={{ route('admin.dashboard') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 600px; margin-top: 100px; }
    </style>
</head>
<body>
<div class="container">
    <div class="alert alert-success">
        {{ $message }}
    </div>
    <p>You will be redirected to the dashboard in 3 seconds...</p>
</div>
</body>
</html>
