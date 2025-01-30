@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="text-center mb-5">
        <h1 class="display-4">Добро пожаловать в наш барбершоп</h1>
        <p class="lead">Мы создаем стильные образы для настоящих мужчин</p>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Стрижки</h3>
                    <p class="card-text">Профессиональные стрижки от опытных барберов</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Бритье</h3>
                    <p class="card-text">Классическое бритье опасной бритвой</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="card-title">Уход за бородой</h3>
                    <p class="card-text">Моделирование и уход за бородой</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="/appointments/create" class="btn btn-primary btn-lg">Записаться онлайн</a>
    </div>
@endsection 