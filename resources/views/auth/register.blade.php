@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="card auth-card">
        <div class="card-body">
            <h1 class="page-title h3 mb-2">Регистрация</h1>
            <p class="auth-description mb-4">Создайте аккаунт, чтобы добавлять лауреатов.</p>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Имя</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" maxlength="255" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="username">Имя пользователя</label>
                    <input class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" minlength="3" maxlength="32" pattern="[a-zA-Z0-9_]+" autocomplete="username" required>
                    <div class="form-text">Латинские буквы, цифры и _</div>
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Электронная почта</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Пароль</label>
                    <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" autocomplete="new-password" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password_confirmation">Повторите пароль</label>
                    <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
                </div>
                <button class="btn btn-olive w-100" type="submit">Зарегистрироваться</button>
            </form>
            <p class="auth-switch mb-0 mt-4">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
        </div>
    </div>
</div>
@endsection
