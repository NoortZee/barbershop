@extends('layouts.app')

@section('title', 'Запись на услугу - Шаг 2')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        @if(session('appointment_data.step1.choice') === 'service')
                            Шаг 2: Выберите мастера
                        @else
                            Шаг 2: Выберите услугу
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST" id="step2Form">
                        @csrf
                        <input type="hidden" name="step" value="2">

                        @if(session('appointment_data.step1.choice') === 'service')
                            <div class="row g-4">
                                @foreach($barbers as $barber)
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            @if($barber->photo)
                                                <img src="{{ asset($barber->photo) }}" class="card-img-top" alt="{{ $barber->name }}">
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $barber->name }}</h5>
                                                <p class="card-text text-muted">{{ $barber->specialization }}</p>
                                                <div class="form-check">
                                                    <input class="form-check-input step2-choice" type="radio" name="barber_id" 
                                                           id="barber{{ $barber->id }}" value="{{ $barber->id }}" required>
                                                    <label class="form-check-label" for="barber{{ $barber->id }}">
                                                        Выбрать мастера
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="row g-4">
                                @foreach($services as $service)
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            @if($service->image)
                                                <img src="{{ asset($service->image) }}" class="card-img-top" alt="{{ $service->name }}">
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $service->name }}</h5>
                                                <p class="card-text">{{ $service->description }}</p>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="text-primary fw-bold">{{ $service->price }} ₽</span>
                                                    <span class="text-muted">{{ $service->duration }} мин</span>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input step2-choice" type="radio" name="service_id" 
                                                           id="service{{ $service->id }}" value="{{ $service->id }}" required>
                                                    <label class="form-check-label" for="service{{ $service->id }}">
                                                        Выбрать услугу
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('appointments.create', ['step' => 1]) }}" class="btn btn-outline-secondary">
                                Назад
                            </a>
                            <button type="submit" class="btn btn-primary" id="continueBtn" disabled>Продолжить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const $form = $('#step2Form');
    const $continueBtn = $('#continueBtn');
    const $choices = $('.step2-choice');

    $choices.on('change', function() {
        $continueBtn.prop('disabled', false);
    });

    $form.on('submit', function(e) {
        if (!$('.step2-choice:checked').length) {
            e.preventDefault();
            alert('Пожалуйста, сделайте выбор перед продолжением');
        }
    });
});
</script>
@endpush
@endsection 
