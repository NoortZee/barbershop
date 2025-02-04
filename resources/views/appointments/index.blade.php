@extends('layouts.app')

@section('title', 'Мои записи')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ auth()->user()->is_admin ? 'Все записи' : 'Мои записи' }}</h2>
                    <a href="{{ route('appointments.create') }}" class="btn btn-premium">
                        <i class="fas fa-plus"></i> Новая запись
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($appointments->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h4>Записей пока нет</h4>
                            <p class="text-muted">Нажмите "Новая запись", чтобы записаться на услугу</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Дата и время</th>
                                        @if(auth()->user()->is_admin)
                                            <th>Клиент</th>
                                        @endif
                                        <th>Мастер</th>
                                        <th>Услуга</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>
                                                {{ $appointment->appointment_time->format('d.m.Y H:i') }}
                                            </td>
                                            @if(auth()->user()->is_admin)
                                                <td>{{ $appointment->user->name }}</td>
                                            @endif
                                            <td>{{ $appointment->barber->name }}</td>
                                            <td>
                                                {{ $appointment->service->name }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ $appointment->service->duration }} мин / {{ $appointment->service->price }}₽
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $appointment->status_color }}">
                                                    {{ $appointment->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('appointments.show', $appointment) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($appointment->can_cancel)
                                                        <form action="{{ route('appointments.destroy', $appointment) }}" 
                                                              method="POST" 
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Вы уверены, что хотите отменить запись?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 