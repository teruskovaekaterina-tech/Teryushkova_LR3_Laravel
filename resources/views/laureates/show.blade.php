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
@endsection
