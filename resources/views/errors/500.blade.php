<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Server Error</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        .error-code {
            font-size: 80px;
            font-weight: bold;
            color: #dc3545;
            margin: 0;
        }
        .error-message {
            color: #666;
            margin-bottom: 20px;
        }
        .debug-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            max-height: 300px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 12px;
            color: #333;
        }
        .btn-group {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-code">500</h1>
        <h2>Server Error</h2>
        <p class="error-message">
            An error occurred while processing your request.
        </p>

        @if (env('APP_DEBUG'))
            <div class="alert alert-warning">
                <strong>Debug Mode Enabled:</strong> Error details are shown below.
            </div>

            @if ($exception)
                <div class="debug-info">
                    <strong>Error Message:</strong><br>
                    {{ $exception->getMessage() }}<br><br>

                    <strong>File:</strong><br>
                    {{ $exception->getFile() }}:{{ $exception->getLine() }}<br><br>

                    <strong>Stack Trace:</strong><br>
                    {{ $exception->getTraceAsString() }}
                </div>
            @endif

            <div class="alert alert-info mt-3">
                <strong>Check logs at:</strong><br>
                <code>storage/logs/laravel.log</code>
            </div>
        @else
            <p class="text-muted">
                Please contact the administrator if this problem persists.
            </p>
        @endif

        <div class="btn-group">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">Go to Login</a>
            <a href="/" class="btn btn-outline-secondary">Go Home</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
