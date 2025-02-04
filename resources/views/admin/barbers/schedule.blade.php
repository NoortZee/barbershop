@extends('layouts.admin')

@section('title', 'Редактирование графика работы')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0">График работы: {{ $barber->name }}</h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.barbers.schedule.update', $barber) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach($daysOfWeek as $day => $dayName)
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input day-checkbox" 
                                               id="day_{{ $day }}" 
                                               data-day="{{ $day }}"
                                               {{ !empty($workingHours[$day]) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="day_{{ $day }}">
                                            {{ $dayName }}
                                        </label>
                                    </div>
                                </div>
                                <div class="time-inputs {{ empty($workingHours[$day]) ? 'd-none' : '' }}">
                                    <div class="input-group">
                                        <input type="text" 
                                               name="working_hours[{{ $day }}]" 
                                               class="form-control time-range @error('working_hours.'.$day) is-invalid @enderror"
                                               value="{{ $workingHours[$day] }}"
                                               placeholder="09:00-19:00"
                                               {{ empty($workingHours[$day]) ? 'disabled' : '' }}>
                                    </div>
                                    @error('working_hours.'.$day)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Сохранить график
                            </button>
                            <a href="{{ route('admin.barbers.index') }}" class="btn btn-secondary">
                                Назад к списку
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="h4 mb-0">Календарь записей</h3>
                    <button class="btn btn-primary" id="blockTimeBtn">
                        Заблокировать время
                    </button>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для блокировки времени -->
<div class="modal fade" id="blockTimeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Заблокировать время</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="blockTimeForm">
                    <div class="mb-3">
                        <label class="form-label">Дата и время начала</label>
                        <input type="text" class="form-control" id="blockStartTime" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Дата и время окончания</label>
                        <input type="text" class="form-control" id="blockEndTime" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Причина (необязательно)</label>
                        <input type="text" class="form-control" id="blockReason">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="saveBlockTime">Сохранить</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация календаря
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'ru',
        initialView: 'timeGridWeek',
        firstDay: 1,
        slotMinTime: '09:00:00',
        slotMaxTime: '21:00:00',
        slotDuration: '00:30:00',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay'
        },
        events: function(info, successCallback, failureCallback) {
            fetch(`/admin/barbers/{{ $barber->id }}/appointments?start=${info.startStr}&end=${info.endStr}`)
                .then(response => response.json())
                .then(data => {
                    successCallback(data.appointments);
                });
        },
        eventDrop: function(info) {
            if (info.event.extendedProps.type === 'blocked') {
                handleBlockedTimeUpdate(info);
            } else {
                info.revert();
            }
        },
        eventResize: function(info) {
            if (info.event.extendedProps.type === 'blocked') {
                handleBlockedTimeUpdate(info);
            } else {
                info.revert();
            }
        },
        eventClick: function(info) {
            if (info.event.extendedProps.type === 'blocked') {
                if (confirm('Разблокировать это время?')) {
                    const id = info.event.id.replace('blocked_', '');
                    fetch(`/admin/blocked-time/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            info.event.remove();
                        }
                    });
                }
            }
        }
    });
    calendar.render();

    // Инициализация выбора времени для блокировки
    const blockStartPicker = flatpickr("#blockStartTime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        locale: "ru",
        minDate: "today"
    });

    const blockEndPicker = flatpickr("#blockEndTime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        locale: "ru",
        minDate: "today"
    });

    // Обработка кнопки блокировки времени
    document.getElementById('blockTimeBtn').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('blockTimeModal'));
        modal.show();
    });

    // Сохранение блокировки времени
    document.getElementById('saveBlockTime').addEventListener('click', function() {
        const startTime = document.getElementById('blockStartTime').value;
        const endTime = document.getElementById('blockEndTime').value;
        const reason = document.getElementById('blockReason').value;

        const formData = new FormData();
        formData.append('start_time', startTime);
        formData.append('end_time', endTime);
        formData.append('reason', reason);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        fetch(`/admin/barbers/{{ $barber->id }}/block-time`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendar.addEvent(data.blocked_time);
                bootstrap.Modal.getInstance(document.getElementById('blockTimeModal')).hide();
                document.getElementById('blockTimeForm').reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при сохранении');
        });
    });

    // Обработка перемещения и изменения размера заблокированного времени
    function handleBlockedTimeUpdate(info) {
        const id = info.event.id.replace('blocked_', '');
        fetch(`/admin/blocked-time/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                start_time: info.event.start.toISOString(),
                end_time: info.event.end.toISOString()
            })
        })
        .then(response => {
            if (!response.ok) {
                info.revert();
            }
        });
    }

    // Обработка чекбоксов дней недели
    document.querySelectorAll('.day-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const timeInputs = this.closest('.mb-3').querySelector('.time-inputs');
            const timeInput = timeInputs.querySelector('input');
            
            if (this.checked) {
                timeInputs.classList.remove('d-none');
                timeInput.disabled = false;
                timeInput.value = '09:00-19:00';
            } else {
                timeInputs.classList.add('d-none');
                timeInput.disabled = true;
                timeInput.value = '';
            }
        });
    });

    // Инициализация выбора времени для графика работы
    flatpickr('.time-range', {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        locale: "ru"
    });
});
</script>
@endpush
@endsection 