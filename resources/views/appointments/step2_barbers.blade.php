@extends('layouts.app')

@section('title', 'Выбор специалиста')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Шаг 2: Выберите специалиста</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="step" value="2">
                        
                        <div class="row g-4">
                            @foreach($barbers as $barber)
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                       name="barber_id" 
                                                       id="barber_{{ $barber->id }}" 
                                                       value="{{ $barber->id }}" 
                                                       required>
                                                <label class="form-check-label" for="barber_{{ $barber->id }}">
                                                    @if($barber->photo)
                                                        <img src="{{ Storage::url($barber->photo) }}" 
                                                             alt="{{ $barber->name }}" 
                                                             class="img-fluid rounded mb-3">
                                                    @endif
                                                    <h5 class="card-title mb-2">{{ $barber->name }}</h5>
                                                    <p class="card-text text-muted mb-2">
                                                        {{ $barber->specialization }}
                                                    </p>
                                                    @if($barber->experience)
                                                        <p class="card-text text-muted">
                                                            Опыт работы: {{ $barber->experience }}
                                                        </p>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('appointments.create') }}" class="btn btn-outline-secondary">
                                Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Продолжить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 