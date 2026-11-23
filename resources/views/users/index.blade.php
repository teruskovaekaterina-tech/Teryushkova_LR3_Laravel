@extends('layouts.app')

@section('content')
<h1 class="page-title mb-4">Пользователи</h1>
<div class="row g-4">
    @forelse($users as $user)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card laureate-card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="mb-2">{{ '@'.$user->username }}</p>
                    <p>Активных лауреатов: {{ $user->laureates_count }}</p>
                    @if($user->is_admin)
                        <span class="badge bg-success mb-3 align-self-start">Администратор</span>
                    @endif
                    <a class="btn btn-primary mt-auto" href="{{ route('users.show', $user) }}">Смотреть лауреатов</a>
                </div>
            </div>
        </div>
    @empty
        <p>Пользователей пока нет.</p>
    @endforelse
</div>
@endsection
