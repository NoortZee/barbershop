@extends('layouts.admin')

@section('title', 'Управление графиком барберов')

@section('content')
<div class="container">
    <div class="row">
        @foreach($barbers as $barber)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h3 class="card-title h4 mb-1">{{ $barber->name }}</h3>
                                <p class="text-muted mb-0">{{ $barber->specialization }}</p>
                            </div>
                            <a href="{{ route('admin.barbers.schedule.edit', $barber) }}" 
                               class="btn btn-primary">
                                Редактировать график
                            </a>
                        </div>
                        
                        @if($barber->working_hours)
                            <div class="mt-3">
                                <h5 class="h6 mb-2">Текущий график работы:</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            @foreach(['mon' => 'Понедельник', 'tue' => 'Вторник', 'wed' => 'Среда', 'thu' => 'Четверг', 'fri' => 'Пятница', 'sat' => 'Суббота', 'sun' => 'Воскресенье'] as $day => $dayName)
                                                <tr>
                                                    <td class="fw-bold" style="width: 40%">{{ $dayName }}</td>
                                                    <td>{{ $barber->working_hours[$day] ?? 'Выходной' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mt-3 mb-0">
                                График работы не установлен
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 