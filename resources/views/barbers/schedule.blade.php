@extends('layouts.app')

@section('title', 'Редактирование графика работы')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">График работы: {{ $barber->name }}</h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('barbers.schedule.update', $barber) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach($daysOfWeek as $day => $dayName)
                            <div class="mb-3">
                                <label class="form-label">{{ $dayName }}</label>
                                <input type="text" 
                                       name="working_hours[{{ $day }}]" 
                                       class="form-control @error('working_hours.'.$day) is-invalid @enderror"
                                       value="{{ $barber->working_hours[$day] ?? '' }}"
                                       placeholder="09:00-18:00">
                                @error('working_hours.'.$day)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Формат: ЧЧ:ММ-ЧЧ:ММ (например, 09:00-18:00) или оставьте пустым для выходного</small>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('barbers.index') }}" class="btn btn-secondary">Назад к списку</a>
                            <button type="submit" class="btn btn-primary">Сохранить график</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 