<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Нобелевские лауреаты по литературе</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<nav class="navbar navbar-dark site-navbar">
    <div class="container d-flex flex-wrap gap-3 align-items-center">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('laureates.index') }}">
            <span class="site-logo">N</span>
            <span>Нобелевские лауреаты</span>
        </a>
        <div class="d-flex flex-wrap gap-2 align-items-center ms-auto">
            @auth
                <a class="btn btn-outline-light btn-sm" href="{{ route('users.index') }}">Пользователи</a>
                <a class="btn btn-outline-light btn-sm" href="{{ route('users.show', auth()->user()) }}">Мои лауреаты</a>
                <span class="text-light small mx-2">{{ auth()->user()->name }}@if(auth()->user()->is_admin) · Администратор @endif</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn load-btn btn-sm">Выйти</button>
                </form>
            @else
                <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Войти</a>
                <a class="btn load-btn btn-sm" href="{{ route('register') }}">Регистрация</a>
            @endauth
        </div>
    </div>
</nav>
<main class="container py-5">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @yield('content')
</main>
<footer class="site-footer">
    <div class="container">Терюшкова Екатерина · Лабораторная работа №4</div>
</footer>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
