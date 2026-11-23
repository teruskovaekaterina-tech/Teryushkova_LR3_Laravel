@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="card laureate-card p-4">
            <h1 class="page-title h3 mb-4">Вход</h1>
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
                <button class="btn btn-success w-100" type="submit">Войти</button>
            </form>
            <a class="mt-3" href="{{ route('register') }}">Нет аккаунта? Зарегистрироваться</a>
        </div>
    </div>
</div>
@endsection
