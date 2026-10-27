
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

        <button id="loadBtn"
                class="btn load-btn"
                type="button">
            Загрузить
        </button>
    </div>
</nav>

<main class="container py-5">
    @yield('content')
</main>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="loadToast" class="toast" role="status">
        <div class="toast-header">
            <strong class="me-auto">Загрузка</strong>
            <button type="button" class="btn-close"
                    data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            На данный момент этот функционал недоступен.
        </div>
    </div>
</div>

<footer class="site-footer">
    <div class="container">
        Терюшкова Екатерина · Лабораторная работа №3
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
