@extends('layouts.app')

@section('title', 'Услуги')

@section('content')
    <h1 class="mb-4">Наши услуги</h1>

    <div class="row">
        @foreach($services as $service)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <p class="card-text">{{ $service->description }}</p>
                        <p class="card-text"><strong>Цена: </strong>{{ $service->price }} ₽</p>
                        <p class="card-text"><small class="text-muted">Длительность: {{ $service->duration }} мин.</small></p>
                        <a href="/appointments/create?service_id={{ $service->id }}" class="btn btn-primary">Записаться</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection 