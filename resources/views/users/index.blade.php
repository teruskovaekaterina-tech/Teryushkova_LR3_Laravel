@extends('layouts.app')

@section('content')
<section class="users-page">
    <div class="users-heading mb-4">
        <h1 class="page-title mb-2">Пользователи</h1>
        <p class="text-muted mb-0">Посмотрите коллекции лауреатов других участников.</p>
    </div>
    <div class="users-grid">
        @forelse($users as $user)
            <article class="card user-card">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                        <div class="min-w-0">
                            <h2 class="user-card__name">{{ $user->name }}</h2>
                            <p class="user-card__handle mb-0">{{ '@'.$user->username }}</p>
                        </div>
                        {{-- Роль видна только администратору: обычным пользователям метка не выводится в HTML. --}}
                        @if(auth()->user()->is_admin && $user->is_admin)
                            <span class="badge admin-badge flex-shrink-0">Администратор</span>
                        @endif
                    </div>
                    <p class="user-card__count mb-4">Активных лауреатов: <strong>{{ $user->laureates_count }}</strong></p>
                    <a class="btn btn-olive w-100 mt-auto" href="{{ route('users.show', $user) }}">Смотреть лауреатов</a>
                </div>
            </article>
        @empty
            <p class="text-muted">Пользователей пока нет.</p>
        @endforelse
    </div>
</section>
@endsection
