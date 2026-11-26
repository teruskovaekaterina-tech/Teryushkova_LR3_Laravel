@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">{{ $laureate->name }}</h1>
<div class="row g-4 align-items-start">
    <div class="col-md-5">
        @if($laureate->image_path)
            <img src="{{ asset('storage/'.$laureate->image_path) }}" class="img-fluid rounded modal-photo" alt="{{ $laureate->name }}">
        @endif
    </div>
    <div class="col-md-7">
        <h3>Лауреат {{ $laureate->award_year }} года</h3>
        <p><strong>Дата рождения:</strong> {{ $laureate->birth_date->locale('ru')->translatedFormat('j F Y') }}</p>
        <p><strong>Гражданство:</strong> {{ $laureate->citizenship }}</p>
        @if($laureate->user)
            <p><strong>Добавил:</strong>
                @auth
                    <a href="{{ route('users.show', $laureate->user) }}">{{ $laureate->user->name }}</a>
                @else
                    {{ $laureate->user->name }}
                @endauth
            </p>
        @endif
        <h4>Произведения</h4>
        <ul>
            @foreach($laureate->works ?? [] as $work)
                <li>{{ $work }}</li>
            @endforeach
        </ul>
        <a href="{{ route('laureates.index') }}" class="btn details-btn">Назад к списку</a>
        @can('manage-laureate', $laureate)
            <a href="{{ route('laureates.edit', $laureate) }}" class="btn btn-warning">Редактировать</a>
        @endcan
    </div>
</div>

<section class="mt-5">
    <h2 class="page-title mb-3">Комментарии</h2>
    @forelse($laureate->comments as $comment)
        <article class="border rounded p-3 mb-3 {{ in_array($comment->user_id, $friendIds) ? 'comment-from-friend bg-warning-subtle border-warning' : 'bg-white' }}">
            <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                <strong>{{ $comment->user->name }}</strong>
                <span class="text-muted small">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
            </div>
            @if(in_array($comment->user_id, $friendIds))
                <span class="badge text-bg-warning mb-2">Друг</span>
            @endif
            <p class="mb-0">{{ $comment->body }}</p>
        </article>
    @empty
        <p class="text-muted">Комментариев пока нет.</p>
    @endforelse

    @auth
        <form action="{{ route('comments.store', $laureate) }}" method="POST" class="mt-4">
            @csrf
            <label for="body" class="form-label">Оставить комментарий</label>
            <textarea name="body" id="body" rows="3" maxlength="1000" required class="form-control">{{ old('body') }}</textarea>
            @error('body')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn btn-olive mt-3">Добавить комментарий</button>
        </form>
    @else
        <p class="mt-4"><a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.</p>
    @endauth
</section>
@endsection
