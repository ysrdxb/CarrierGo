<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger">
                    <h4>Error</h4>
                    <p>{{ $error ?? 'An error occurred' }}</p>
                    <a href="/carriergo/login" class="btn btn-primary">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
