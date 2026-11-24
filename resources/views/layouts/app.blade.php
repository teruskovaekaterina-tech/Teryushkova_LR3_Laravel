
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Нобелевские лауреаты по литературе</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<nav class="navbar navbar-dark site-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3"
           href="{{ route('laureates.index') }}">
            <span class="site-logo">N</span>
            <span>Нобелевские лауреаты</span>
        </a>

        <a href="{{ route('laureates.create') }}" class="btn load-btn">
            Добавить лауреата
        </a>
    </div>
</nav>

<main class="container py-5">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        Терюшкова Екатерина · Лабораторная работа №3
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
