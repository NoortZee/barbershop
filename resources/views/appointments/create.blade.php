@extends('layouts.app')

@section('title', 'Записаться')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center mb-0">Записаться на приём</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/appointments" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Выберите услугу</label>
                            <select name="service_id" id="service_id" class="form-control" required>
                                <option value="">-- Выберите услугу --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - {{ $service->price }}₽
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="barber_id" class="form-label">Выберите барбера</label>
                            <select name="barber_id" id="barber_id" class="form-control" required>
                                <option value="">-- Выберите барбера --</option>
                                @foreach($barbers as $barber)
                                    <option value="{{ $barber->id }}" {{ old('barber_id') == $barber->id ? 'selected' : '' }}>
                                        {{ $barber->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Дата</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                                   value="{{ old('appointment_date') }}" required min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label for="appointment_time" class="form-label">Время</label>
                            <select name="appointment_time" id="appointment_time" class="form-control" required>
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

                        <div class="mb-3">
                            <label for="client_name" class="form-label">Ваше имя</label>
                            <input type="text" class="form-control" id="client_name" name="client_name" 
                                   value="{{ old('client_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="{{ old('phone') }}" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Записаться</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 