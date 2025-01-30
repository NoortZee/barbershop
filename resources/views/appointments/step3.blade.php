@extends('layouts.app')

@section('title', 'Запись на услугу - Шаг 3')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Шаг 3: Выберите дату и время</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="step" value="3">

                        <div class="mb-4">
                            <h5>Выбранные параметры:</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Мастер:</strong> {{ $barber->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Услуга:</strong> {{ $service->name }} ({{ $service->duration }} мин)</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="appointment_date" class="form-label">Выберите дату</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                                   min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Доступное время</label>
                            <div id="timeSlots" class="row g-2">
                                <!-- Временные слоты будут добавлены через JavaScript -->
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Дополнительные пожелания</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('appointments.create', ['step' => 2]) }}" class="btn btn-outline-secondary">
                                Назад
                            </a>
                            <button type="submit" class="btn btn-primary">Записаться</button>
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
    const dateInput = document.getElementById('appointment_date');
    const timeSlotsContainer = document.getElementById('timeSlots');
    
    // Устанавливаем минимальную дату
    const today = new Date();
    dateInput.min = today.toISOString().split('T')[0];
    
    // Генерируем временные слоты при изменении даты
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        if (!selectedDate) return;

        // Очищаем контейнер со слотами
        timeSlotsContainer.innerHTML = '';
        
        // Получаем рабочие часы мастера для выбранного дня
        fetch(`/api/available-times?date=${selectedDate}&barber_id={{ $barber->id }}&service_id={{ $service->id }}`)
            .then(response => response.json())
            .then(data => {
                if (data.available_times && data.available_times.length > 0) {
                    data.available_times.forEach(time => {
                        const col = document.createElement('div');
                        col.className = 'col-auto';
                        
                        const label = document.createElement('label');
                        label.className = 'btn btn-outline-primary time-slot';
                        
                        const input = document.createElement('input');
                        input.type = 'radio';
                        input.name = 'appointment_time';
                        input.value = time;
                        input.className = 'btn-check';
                        input.required = true;
                        
                        const span = document.createElement('span');
                        span.textContent = time;
                        
                        label.appendChild(input);
                        label.appendChild(span);
                        col.appendChild(label);
                        timeSlotsContainer.appendChild(col);
                    });
                } else {
                    timeSlotsContainer.innerHTML = '<div class="col-12"><p class="text-danger">Нет доступного времени на выбранную дату</p></div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                timeSlotsContainer.innerHTML = '<div class="col-12"><p class="text-danger">Ошибка при загрузке доступного времени</p></div>';
            });
    });
});
</script>

<style>
.time-slot {
    min-width: 80px;
    margin: 5px;
}

.time-slot input[type="radio"] {
    display: none;
}

.time-slot input[type="radio"]:checked + span {
    background-color: var(--bs-primary);
    color: white;
}

.time-slot span {
    display: block;
    padding: 5px 10px;
    text-align: center;
    border-radius: 4px;
    cursor: pointer;
}
</style>
@endpush

@endsection 
