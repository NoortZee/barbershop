@extends('layouts.app')

@section('title', 'Выбор услуг')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Шаг 2: Выберите услуги</h4>
                    <div class="text-end">
                        Итого: <strong id="totalPrice">0</strong> ₽
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST" id="servicesForm">
                        @csrf
                        <input type="hidden" name="step" value="2">
                        
                        <div class="row g-4">
                            @foreach($services as $service)
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                        <div class="form-check">
                                                <input class="form-check-input service-checkbox" type="checkbox" 
                                                   name="service_ids[]" 
                                                   id="service_{{ $service->id }}" 
                                                   value="{{ $service->id }}"
                                                   data-price="{{ $service->price }}"
                                                   data-duration="{{ $service->duration }}">
                                                <label class="form-check-label" for="service_{{ $service->id }}">
                                                    <h5 class="card-title mb-2">{{ $service->name }}</h5>
                                                    <p class="card-text text-muted mb-2">
                                                {{ $service->description }}
                                            </p>
                                                    <p class="card-text">
                                                        <strong>{{ $service->price }} ₽</strong>
                                                        <span class="text-muted">
                                                            • {{ $service->duration }} мин
                                                        </span>
                                                    </p>
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <a href="{{ route('appointments.create') }}" class="btn btn-outline-secondary">
                                Назад
                            </a>
                            <div>
                                <span class="me-3">
                                    Длительность: <strong id="totalDuration">0</strong> мин
                                </span>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                Продолжить
                            </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.service-checkbox');
    const totalPriceElement = document.getElementById('totalPrice');
    const totalDurationElement = document.getElementById('totalDuration');
    const submitBtn = document.getElementById('submitBtn');
    
function updateTotals() {
    let totalPrice = 0;
    let totalDuration = 0;
    let checkedCount = 0;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
            totalPrice += parseInt(checkbox.dataset.price);
            totalDuration += parseInt(checkbox.dataset.duration);
            checkedCount++;
            }
        });
        
        totalPriceElement.textContent = totalPrice;
        totalDurationElement.textContent = totalDuration;
        submitBtn.disabled = checkedCount === 0;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotals);
    });
});
</script>
@endpush

@endsection 