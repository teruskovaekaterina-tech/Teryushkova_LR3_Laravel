
<div class="modal fade"
     id="laureateModal{{ $laureate->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ $laureate->name }}</h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                @if($laureate->image_path)
                    <img
                        src="{{ asset('storage/' . $laureate->image_path) }}"
                        class="img-fluid rounded mb-4"
                        alt="{{ $laureate->name }}"
                        style="max-height: 350px; width: 100%; object-fit: contain;">
                @endif

                <h5>Лауреат {{ $laureate->award_year }} года</h5>

                <p>
                    <strong>Дата рождения:</strong>
                    {{ $laureate->birth_date->locale('ru')->translatedFormat('j F Y') }}
                </p>

                <p>
                    <strong>Гражданство:</strong>
                    {{ $laureate->citizenship }}
                </p>

                <h5>Произведения</h5>

                <ul>
                    @foreach($laureate->works ?? [] as $work)
                        <li>{{ $work }}</li>
                    @endforeach
                </ul>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Закрыть
                </button>
            </div>

        </div>
    </div>
</div>
