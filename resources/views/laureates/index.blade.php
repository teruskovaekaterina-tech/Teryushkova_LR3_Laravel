
@extends('layouts.app')

@section('content')

<h1 class="page-title mb-4">
    Нобелевские лауреаты по литературе
</h1>

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

                    <a
                        href="{{ route('laureates.show', $laureate) }}"
                        class="btn btn-primary mt-auto">
                        Подробнее
                    </a>

                </div>
            </div>
        </div>

    @empty

        <p>Лауреатов пока нет.</p>

    @endforelse

</div>

@endsection
