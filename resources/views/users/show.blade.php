@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h1 class="page-title mb-1">Лауреаты пользователя {{ $user->name }}</h1>
        <p class="text-muted mb-0">{{ '@'.$user->username }} · Всего записей: {{ $laureates->count() }}</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Все пользователи</a>
</div>
<div class="row g-4">
    @forelse($laureates as $laureate)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card laureate-card h-100">
                @if($laureate->image_path)
                    <img src="{{ asset('storage/'.$laureate->image_path) }}" class="card-img-top" alt="{{ $laureate->name }}" style="height: 240px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $laureate->name }}</h5>
                    <p>Год премии: {{ $laureate->award_year }}</p>
                    @if($laureate->trashed())
                        <span class="badge bg-secondary align-self-start mb-3">Удалён</span>
                    @else
                        <a href="{{ route('laureates.show', $laureate) }}" class="btn btn-primary mt-auto mb-2">Подробнее</a>
                        @can('manage-laureate', $laureate)
                            <a href="{{ route('laureates.edit', $laureate) }}" class="btn btn-warning mb-2">Редактировать</a>
                            <form action="{{ route('laureates.destroy', $laureate) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100 mb-2">Удалить</button>
                            </form>
                        @endcan
                    @endif
                    @if($laureate->trashed())
                        @can('restore-laureate', $laureate)
                            <form action="{{ route('laureates.restore', $laureate) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">Восстановить</button>
                            </form>
                        @endcan
                    @endif
                    @can('force-delete-laureate', $laureate)
                        <form action="{{ route('laureates.force', $laureate) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">Удалить навсегда</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @empty
        <p>У этого пользователя пока нет лауреатов.</p>
    @endforelse
</div>
@endsection
