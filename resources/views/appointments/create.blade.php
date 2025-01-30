@extends('layouts.app')

@section('title', 'Записаться')

@section('content')
<div class="appointment-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="appointment-card">
                    <div class="appointment-header">
                        <h2 class="text-center">Записаться на приём</h2>
                        <div class="steps-progress mb-4">
                            <div class="step active" id="step1">1. Выбор услуг</div>
                            <div class="step" id="step2">2. Выбор даты и времени</div>
                        </div>
                    </div>

                <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST">
                    @csrf
                        <div id="step1-content" class="step-content active">
                    <div class="services-selection">
                        @foreach($services as $service)
                                <div class="service-item">
                                    <input type="checkbox" name="selected_services[]" 
                                           value="{{ $service->id }}" 
                                           id="service_{{ $service->id }}"
                                           class="service-checkbox">
                                    <label for="service_{{ $service->id }}" class="service-label">
                                        <span class="service-name">{{ $service->name }}</span>
                                        <span class="service-price">{{ $service->price }}₽</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="continue-to-step2" class="btn btn-premium btn-lg w-100 mt-4" disabled>
                                Продолжить
                            </button>
                        </div>

                        <div id="step2-content" class="step-content">
                            <div class="form-group">
                                <label for="barber_id" class="form-label">
                                    <i class="fas fa-user-tie"></i> Выберите барбера
                                </label>
                                <select name="barber_id" id="barber_id" class="form-select custom-select" required>
                                    <option value="">-- Выберите барбера --</option>
                                    @foreach($barbers as $barber)
                                        <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                        @endforeach
                                </select>
                    </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="appointment_date" class="form-label">
                                            <i class="fas fa-calendar"></i> Дата
                                        </label>
                                        <input type="date" class="form-control custom-input" id="appointment_date" 
                                               name="appointment_date" required min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="appointment_time" class="form-label">
                                            <i class="fas fa-clock"></i> Время
                                        </label>
                                        <select name="appointment_time" id="appointment_time" class="form-select custom-select" required>
                                            <option value="">-- Выберите время --</option>
                                            @for($hour = 9; $hour <= 20; $hour++)
                                                @foreach(['00', '30'] as $minute)
                                                    <option value="{{ sprintf('%02d:%s', $hour, $minute) }}">
                                                        {{ sprintf('%02d:%s', $hour, $minute) }}
                                                    </option>
                                                @endforeach
                                            @endfor
                                        </select>
                                </div>
                            </div>
                        </div>

                            <div class="mt-4">
                                <button type="button" id="back-to-step1" class="btn btn-outline-secondary btn-lg me-2">
                                    Назад
                                </button>
                                <button type="submit" class="btn btn-premium btn-lg">
                                    Записаться
                                </button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<style>
.appointment-section {
    padding: 4rem 0;
}

.appointment-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 2.5rem;
}

.steps-progress {
    display: flex;
    justify-content: space-between;
    margin: 2rem 0;
}

.step {
    flex: 1;
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    margin: 0 0.5rem;
    border-radius: 10px;
    font-weight: 500;
    color: #6c757d;
}

.step.active {
    background: var(--accent-color);
    color: white;
}

.step-content {
    display: none;
}

.step-content.active {
    display: block;
}

.service-item {
    margin-bottom: 1rem;
}

.service-label {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
}

.service-checkbox {
    display: none;
}

.service-checkbox:checked + .service-label {
    border-color: var(--accent-color);
    background-color: rgba(var(--accent-color-rgb), 0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const continueButton = document.getElementById('continue-to-step2');
    const backButton = document.getElementById('back-to-step1');
    const step1Content = document.getElementById('step1-content');
    const step2Content = document.getElementById('step2-content');
    const step1Indicator = document.getElementById('step1');
    const step2Indicator = document.getElementById('step2');

    // Проверяем выбрана ли хотя бы одна услуга
    function updateContinueButton() {
        const anyChecked = Array.from(serviceCheckboxes).some(checkbox => checkbox.checked);
        continueButton.disabled = !anyChecked;
    }

    // Добавляем обработчики для чекбоксов
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateContinueButton);
    });

    // Переход на второй шаг
    continueButton.addEventListener('click', function() {
        step1Content.classList.remove('active');
        step2Content.classList.add('active');
        step1Indicator.classList.remove('active');
        step2Indicator.classList.add('active');
    });

    // Возврат на первый шаг
    backButton.addEventListener('click', function() {
        step2Content.classList.remove('active');
        step1Content.classList.add('active');
        step2Indicator.classList.remove('active');
        step1Indicator.classList.add('active');
    });
});
</script>
@endsection 