@extends('layouts.app')

@section('title', 'Запись онлайн')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">С чего начать?</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <a href="{{ route('appointments.store') }}" 
                               onclick="event.preventDefault(); document.getElementById('choice-service-form').submit();" 
                               class="card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-3">Выбрать услуги</h5>
                                    <img src="/images/services.jpg" alt="Услуги" class="img-fluid rounded mb-3" style="max-height: 150px; object-fit: cover;">
                                </div>
                            </a>
                            <form id="choice-service-form" action="{{ route('appointments.store') }}" method="POST" class="d-none">
                                @csrf
                                <input type="hidden" name="step" value="1">
                                <input type="hidden" name="choice" value="service">
                            </form>
                        </div>
                        
                        <div class="col-md-6">
                            <a href="{{ route('appointments.store') }}" 
                               onclick="event.preventDefault(); document.getElementById('choice-barber-form').submit();" 
                               class="card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-3">Выбрать специалиста</h5>
                                    <img src="/images/barbers.jpg" alt="Специалисты" class="img-fluid rounded mb-3" style="max-height: 150px; object-fit: cover;">
                                </div>
                            </a>
                            <form id="choice-barber-form" action="{{ route('appointments.store') }}" method="POST" class="d-none">
                                @csrf
                                <input type="hidden" name="step" value="1">
                                <input type="hidden" name="choice" value="barber">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
