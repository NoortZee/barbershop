@extends('layouts.app')

@section('title', 'Наши барберы')

@section('content')
    <h1 class="mb-4">Наши барберы</h1>

    <div class="row">
        @foreach($barbers as $barber)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($barber->photo)
                        <img src="{{ asset('storage/' . $barber->photo) }}" class="card-img-top" alt="{{ $barber->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $barber->name }}</h5>
                        <p class="card-text">{{ $barber->description }}</p>
                        <p class="card-text">
                            <small class="text-muted">Опыт работы: {{ $barber->experience }} лет</small>
                        </p>
                        <a href="/appointments/create?barber_id={{ $barber->id }}" class="btn btn-primary">Записаться</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection 