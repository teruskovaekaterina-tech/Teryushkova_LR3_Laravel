
@extends('layouts.app')

@section('content')

<h1 class="page-title mb-4">
    {{ $laureate->exists ? 'Редактировать лауреата' : 'Добавить лауреата' }}
</h1>

<form action="{{ $laureate->exists ? route('laureates.update', $laureate) : route('laureates.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    @if($laureate->exists)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label class="form-label">Имя лауреата</label>
        <input type="text" name="name"
               class="form-control"
               value="{{ old('name', $laureate->name) }}" required>
        @error('name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Дата рождения</label>
        <input type="date" name="birth_date"
               class="form-control"
               value="{{ old('birth_date', $laureate->birth_date?->format('Y-m-d')) }}" required>
        @error('birth_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Гражданство</label>
        <input type="text" name="citizenship"
               class="form-control"
               value="{{ old('citizenship', $laureate->citizenship) }}" required>
        @error('citizenship')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Год получения премии</label>
        <input type="number" name="award_year"
               class="form-control"
               value="{{ old('award_year', $laureate->award_year) }}"
               min="1901" max="2100" required>
        @error('award_year')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Фотография лауреата</label>

        @if($laureate->image_path)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $laureate->image_path) }}"
                     alt="{{ $laureate->name }}"
                     style="max-width: 150px;">
            </div>
        @endif

        <input type="file" name="image"
               class="form-control"
               accept=".jpg,.jpeg,.png">

        @error('image')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">
            Произведения (каждое с новой строки)
        </label>
        <textarea name="works_text"
                  class="form-control"
                  rows="5" required>{{ old('works_text', implode("\n", $laureate->works ?? [])) }}</textarea>
        @error('works_text')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-success">
        {{ $laureate->exists ? 'Сохранить изменения' : 'Добавить лауреата' }}
    </button>

    <a href="{{ route('laureates.index') }}"
       class="btn btn-secondary">
        Назад
    </a>

</form>

@endsection
