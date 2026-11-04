
@extends('layouts.app')

@section('content')

<h1 class="page-title mb-4">
    Нобелевские лауреаты по литературе
</h1>

<a href="{{ route('laureates.create') }}"
   class="btn btn-success mb-4">
    Добавить лауреата
</a>

<p>Всего лауреатов: {{ $laureates->count() }}</p>

<div class="row g-4">

    @forelse($laureates as $laureate)

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card laureate-card h-100">

                @if($laureate->image_path)
                    <img
                        src="{{ asset('storage/' . $laureate->image_path) }}"
                        class="card-img-top"
                        alt="{{ $laureate->name }}"
                        style="height: 240px; object-fit: cover;">
                @endif

                <div class="card-body d-flex flex-column">

                    <h5 class="card-title">
                        {{ $laureate->name }}
                    </h5>

                    <p>
                        <strong>Год премии:</strong>
                        {{ $laureate->award_year }}
                    </p>

                    <p>
                        <strong>Гражданство:</strong>
                        {{ $laureate->citizenship }}
                    </p>

                    <div class="d-flex flex-column gap-2 mt-auto">

                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#laureateModal{{ $laureate->id }}">
                            Подробнее
                        </button>

                        <a href="{{ route('laureates.edit', $laureate) }}"
                           class="btn btn-warning">
                            Редактировать
                        </a>

                        <form action="{{ route('laureates.destroy', $laureate) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger w-100">
                                Удалить
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>

        @include('laureates.modal', ['laureate' => $laureate])

    @empty

        <p>Лауреатов пока нет.</p>

    @endforelse

</div>

@endsection
