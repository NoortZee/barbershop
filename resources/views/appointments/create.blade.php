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
                        <p class="text-center text-muted">Создайте свой идеальный образ вместе с нами</p>
                    </div>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/appointments" method="POST" class="appointment-form">
                        @csrf
                        <div class="form-group">
                            <label for="service_id" class="form-label">
                                <i class="fas fa-cut"></i> Выберите услугу
                            </label>
                            <select name="service_id" id="service_id" class="form-select custom-select" required>
                                <option value="">-- Выберите услугу --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - {{ $service->price }}₽
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="barber_id" class="form-label">
                                <i class="fas fa-user-tie"></i> Выберите барбера
                            </label>
                            <select name="barber_id" id="barber_id" class="form-select custom-select" required>
                                <option value="">-- Выберите барбера --</option>
                                @foreach($barbers as $barber)
                                    <option value="{{ $barber->id }}" 
                                        {{ old('barber_id') == $barber->id ? 'selected' : '' }}
                                        {{ $selectedBarberId == $barber->id ? 'selected' : '' }}>
                                        {{ $barber->name }}
                                    </option>
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
                                           name="appointment_date" value="{{ old('appointment_date') }}" 
                                           required min="{{ date('Y-m-d') }}">
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
                                                <option value="{{ sprintf('%02d:%s', $hour, $minute) }}"
                                                    {{ old('appointment_time') == sprintf('%02d:%s', $hour, $minute) ? 'selected' : '' }}>
                                                    {{ sprintf('%02d:%s', $hour, $minute) }}
                                                </option>
                                            @endforeach
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="client_name" class="form-label">
                                <i class="fas fa-user"></i> Ваше имя
                            </label>
                            <input type="text" class="form-control custom-input" id="client_name" 
                                   name="client_name" value="{{ old('client_name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i> Телефон
                            </label>
                            <input type="tel" class="form-control custom-input" id="phone" 
                                   name="phone" value="{{ old('phone') }}" required>
                        </div>

                        <div class="form-submit">
                            <button type="submit" class="btn btn-premium btn-lg w-100">
                                <i class="fas fa-check-circle"></i> Записаться
                            </button>
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
    background: linear-gradient(to bottom, var(--light-bg) 0%, #ffffff 100%);
}

.appointment-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 2.5rem;
    transition: all 0.3s ease;
}

.appointment-header {
    margin-bottom: 2rem;
}

.appointment-header h2 {
    color: var(--accent-color);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    display: block;
}

.form-label i {
    margin-right: 0.5rem;
    color: var(--accent-color);
}

.custom-select,
.custom-input {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.custom-select:focus,
.custom-input:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--accent-color-rgb), 0.25);
}

.btn-premium {
    background: var(--accent-color);
    border: none;
    padding: 1rem 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(var(--accent-color-rgb), 0.4);
}

.form-submit {
    margin-top: 2rem;
}

.alert-danger {
    border-radius: 10px;
    border: none;
    background-color: #fee2e2;
    color: #991b1b;
}

.alert-danger ul {
    padding-left: 1rem;
}

@media (max-width: 768px) {
    .appointment-card {
        padding: 1.5rem;
    }
}
</style>
@endsection 