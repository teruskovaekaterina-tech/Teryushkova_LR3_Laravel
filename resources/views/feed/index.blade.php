@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="page-title mb-2">Лента друзей</h1>
    <p class="text-muted mb-0">Новые лауреаты, добавленные вашими друзьями.</p>
</div>

<div class="row g-4">
    @forelse($laureates as $laureate)
        <div class="col-12 col-md-6 col-lg-4">
            <article class="card laureate-card h-100">
                @if($laureate->image_path)
                    <img src="{{ asset('storage/'.$laureate->image_path) }}" class="card-img-top" alt="{{ $laureate->name }}" style="height: 220px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h2 class="h5 card-title">{{ $laureate->name }}</h2>
                    <p class="mb-2">Год премии: {{ $laureate->award_year }}</p>
                    <p class="text-muted small">Добавил: {{ $laureate->user->name }}</p>
                    <p class="text-muted small">Дата добавления: {{ $laureate->created_at->format('d.m.Y H:i') }}</p>
                    <a href="{{ route('laureates.show', $laureate) }}" class="btn btn-olive mt-auto">Подробнее</a>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12">
            <p class="text-muted">В ленте пока нет новых лауреатов. Добавьте пользователей в друзья.</p>
            <a href="{{ route('users.index') }}" class="btn btn-olive">Найти пользователей</a>
        </div>
    @endforelse
</div>
@endsection
