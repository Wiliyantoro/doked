<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .main-content {
            display: flex;
            flex: 1;
            flex-direction: column;
        }
        .sidebar {
            min-height: 100%;
            background-color: #343a40;
            color: white;
            padding: 15px;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
        }
        @media (min-width: 768px) {
            .main-content {
                flex-direction: row;
            }
        }
    </style>
    @stack('styles') <!-- Tempat untuk section styles -->
</head>
<body>
    @include('layouts.header') <!-- Memanggil header -->

    <div class="main-content">
        <div class="sidebar">
            @include('layouts.sidebar') <!-- Memanggil sidebar -->
        </div>
        <div class="content">
            @yield('content') <!-- Tempat untuk konten utama -->
        </div>
    </div>

    @include('layouts.footer') <!-- Memanggil footer -->

    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Load Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <!-- Load Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    @stack('scripts')
</body>
</html>
