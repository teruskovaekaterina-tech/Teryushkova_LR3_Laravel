@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="card auth-card">
        <div class="card-body">
            <h1 class="page-title h3 mb-2">Вход</h1>
            <p class="auth-description mb-4">Войдите, чтобы управлять своими лауреатами.</p>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Электронная почта</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Пароль</label>
                    <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" autocomplete="current-password" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember" value="1">
                    <label class="form-check-label" for="remember">Запомнить меня</label>
                </div>
                <button class="btn btn-olive w-100" type="submit">Войти</button>
            </form>
            <p class="auth-switch mb-0 mt-4">Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
        </div>
    </div>
</div>
@endsection
