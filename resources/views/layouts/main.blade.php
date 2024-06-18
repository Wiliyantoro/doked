<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Include Font Awesome for icons -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .content-wrapper {
            display: flex;
            flex: 1;
            overflow: auto;
        }
        .sidebar {
            height: 100%;
            padding: 15px;
        }
        .content {
            padding: 20px;
            flex: 1;
        }
        .header, .footer {
            padding: 10px 20px;
        }
        .footer {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        @include('layouts.header')

        <div class="content-wrapper">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar bg-dark text-white">
                @include('layouts.sidebar')
            </div>

            <!-- Main content -->
            <div class="col-md-10 content">
                @yield('content')
            </div>
        </div>

        @include('layouts.footer')
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
