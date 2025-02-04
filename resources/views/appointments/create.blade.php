@extends('layouts.app')

@section('title', 'Записаться')

@section('content')
<div class="appointment-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="appointment-card">
                    <div class="appointment-header">
                        <h2 class="text-center">Записаться на приём</h2>
                        <div class="steps-progress mb-4">
                            <div class="step active clickable" id="step1" role="button">1. Выбор услуги</div>
                            <div class="step clickable" id="step2" role="button">2. Выбор мастера и времени</div>
                        </div>
                    </div>

                    <form action="{{ route('appointments.store') }}" method="POST" id="appointmentForm" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Шаг 1: Выбор услуги -->
                        <div id="step1-content" class="step-content active">
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-cut"></i> Выберите услуги
                                </label>
                                <div class="services-list">
                                    @foreach($services as $service)
                                        <div class="service-item">
                                            <input type="checkbox" 
                                                   name="service_id" 
                                                   value="{{ $service->id }}" 
                                                   id="service_{{ $service->id }}"
                                                   class="service-checkbox"
                                                   data-duration="{{ $service->duration }}" 
                                                   data-price="{{ $service->price }}"
                                                   data-name="{{ $service->name }}">
                                            <label for="service_{{ $service->id }}" class="service-label">
                                                <div class="service-info">
                                                    <span class="service-name">{{ $service->name }}</span>
                                                    <div class="service-details">
                                                        <span class="service-duration"><i class="fas fa-clock"></i> {{ $service->duration }} мин</span>
                                                        <span class="service-price">{{ $service->price }}₽</span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="selected-services-info mb-4" style="display: none;">
                                <div class="alert alert-info">
                                    <h6 class="mb-2">Выбранные услуги:</h6>
                                    <div class="services-details"></div>
                                    <div class="services-total mt-2">
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span>Общая длительность:</span>
                                            <span class="total-duration"></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Общая стоимость:</span>
                                            <span class="total-price"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="button" id="continue-to-step2" class="btn btn-premium btn-lg" disabled>
                                    Продолжить
                                </button>
                            </div>
                        </div>

                        <!-- Шаг 2: Выбор мастера и времени -->
                        <div id="step2-content" class="step-content">
                            <div class="selected-info mb-4">
                                <div class="alert alert-info">
                                    <h6 class="mb-2">Выбранные услуги:</h6>
                                    <div class="selected-services"></div>
                                    <div class="services-total mt-2">
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span>Общая длительность:</span>
                                            <span class="total-duration-step2"></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Общая стоимость:</span>
                                            <span class="total-price-step2"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="calendar-wrapper mb-4">
                                <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Выберите дату и время</h5>
                                    <div class="d-flex align-items-center">
                                        <select id="barber_filter" class="form-select me-3">
                                            <option value="all">Все мастера</option>
                                            @foreach($barbers as $barber)
                                                <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="calendar-strip mb-4">
                                    <div class="month-navigation d-flex justify-content-between align-items-center mb-3">
                                        <button type="button" class="btn btn-link text-dark prev-month">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <h6 class="month-title mb-0">Февраль</h6>
                                        <button type="button" class="btn btn-link text-dark next-month">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                    <div class="days-strip">
                                        <div class="days-grid">
                                            @foreach(['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'] as $day)
                                                <div class="day-name">{{ $day }}</div>
                                            @endforeach
                                            <div class="day-button disabled">4</div>
                                            <div class="day-button">5</div>
                                            <div class="day-button">6</div>
                                            <div class="day-button active">7</div>
                                            <div class="day-button">8</div>
                                            <div class="day-button weekend">9</div>
                                            <div class="day-button weekend">10</div>
                                            <!-- Дополнительные дни будут добавляться динамически -->
                                        </div>
                                    </div>
                                </div>

                                <div class="time-slots-container">
                                    <div class="no-slots-message" style="display: none;">
                                        <div class="alert alert-info text-center">
                                            На выбранную дату нет записи
                                        </div>
                                    </div>
                                    <div class="time-sections">
                                        <div class="time-section mb-4">
                                            <h6 class="time-section-title mb-3">Утро</h6>
                                            <div class="time-grid">
                                                <button type="button" class="time-slot" data-time="09:00">09:00</button>
                                                <button type="button" class="time-slot" data-time="09:15">09:15</button>
                                                <button type="button" class="time-slot" data-time="09:30">09:30</button>
                                                <button type="button" class="time-slot" data-time="09:45">09:45</button>
                                                <button type="button" class="time-slot" data-time="10:00">10:00</button>
                                                <button type="button" class="time-slot" data-time="10:15">10:15</button>
                                                <button type="button" class="time-slot" data-time="10:30">10:30</button>
                                                <button type="button" class="time-slot" data-time="10:45">10:45</button>
                                                <button type="button" class="time-slot" data-time="11:00">11:00</button>
                                                <button type="button" class="time-slot" data-time="11:15">11:15</button>
                                                <button type="button" class="time-slot" data-time="11:30">11:30</button>
                                                <button type="button" class="time-slot" data-time="11:45">11:45</button>
                                            </div>
                                        </div>

                                        <div class="time-section mb-4">
                                            <h6 class="time-section-title mb-3">День</h6>
                                            <div class="time-grid">
                                                <button type="button" class="time-slot" data-time="12:00">12:00</button>
                                                <button type="button" class="time-slot" data-time="12:15">12:15</button>
                                                <button type="button" class="time-slot" data-time="12:30">12:30</button>
                                                <button type="button" class="time-slot" data-time="12:45">12:45</button>
                                                <button type="button" class="time-slot" data-time="13:00">13:00</button>
                                                <button type="button" class="time-slot" data-time="13:15">13:15</button>
                                                <button type="button" class="time-slot" data-time="13:30">13:30</button>
                                                <button type="button" class="time-slot" data-time="13:45">13:45</button>
                                                <button type="button" class="time-slot" data-time="14:00">14:00</button>
                                                <button type="button" class="time-slot" data-time="14:15">14:15</button>
                                                <button type="button" class="time-slot" data-time="14:30">14:30</button>
                                                <button type="button" class="time-slot" data-time="14:45">14:45</button>
                                                <button type="button" class="time-slot" data-time="15:00">15:00</button>
                                                <button type="button" class="time-slot" data-time="15:15">15:15</button>
                                                <button type="button" class="time-slot" data-time="15:30">15:30</button>
                                                <button type="button" class="time-slot" data-time="15:45">15:45</button>
                                                <button type="button" class="time-slot" data-time="16:00">16:00</button>
                                                <button type="button" class="time-slot" data-time="16:15">16:15</button>
                                                <button type="button" class="time-slot" data-time="16:30">16:30</button>
                                                <button type="button" class="time-slot" data-time="16:45">16:45</button>
                                            </div>
                                        </div>

                                        <div class="time-section mb-4">
                                            <h6 class="time-section-title mb-3">Вечер</h6>
                                            <div class="time-grid">
                                                <button type="button" class="time-slot" data-time="17:00">17:00</button>
                                                <button type="button" class="time-slot" data-time="17:15">17:15</button>
                                                <button type="button" class="time-slot" data-time="17:30">17:30</button>
                                                <button type="button" class="time-slot" data-time="17:45">17:45</button>
                                                <button type="button" class="time-slot" data-time="18:00">18:00</button>
                                                <button type="button" class="time-slot" data-time="18:15">18:15</button>
                                                <button type="button" class="time-slot" data-time="18:30">18:30</button>
                                                <button type="button" class="time-slot" data-time="18:45">18:45</button>
                                                <button type="button" class="time-slot" data-time="19:00">19:00</button>
                                                <button type="button" class="time-slot" data-time="19:15">19:15</button>
                                                <button type="button" class="time-slot" data-time="19:30">19:30</button>
                                                <button type="button" class="time-slot" data-time="19:45">19:45</button>
                                                <button type="button" class="time-slot" data-time="20:00">20:00</button>
                                                <button type="button" class="time-slot" data-time="20:15">20:15</button>
                                                <button type="button" class="time-slot" data-time="20:30">20:30</button>
                                                <button type="button" class="time-slot" data-time="20:45">20:45</button>
                                                <button type="button" class="time-slot" data-time="21:00">21:00</button>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="appointment_date" id="appointment_date" required>
                                    <input type="hidden" name="appointment_time" id="appointment_time" required>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" id="back-to-step1" class="btn btn-outline-secondary btn-lg">
                                        Назад
                                    </button>
                                    <button type="submit" class="btn btn-premium btn-lg" id="submit-button" disabled>
                                        Записаться
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.appointment-section {
    padding: 4rem 0;
}

.appointment-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 2.5rem;
}

.steps-progress {
    display: flex;
    justify-content: space-between;
    margin: 2rem 0;
}

.step {
    flex: 1;
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    margin: 0 0.5rem;
    border-radius: 10px;
    font-weight: 500;
    color: #6c757d;
    transition: all 0.3s ease;
    cursor: pointer;
    user-select: none;
}

.step:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.step.active {
    background: var(--accent-color);
    color: white;
    transform: translateY(-2px);
}

.step.active:hover {
    background: var(--accent-color-dark);
}

.step-content {
    display: none;
}

.step-content.active {
    display: block;
}

.btn-premium {
    background: var(--accent-color);
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-premium:hover {
    background: var(--accent-color-dark);
    transform: translateY(-2px);
}

.btn-premium:disabled {
    background: #6c757d;
    transform: none;
}

.form-select, .form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-select:focus, .form-control:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--accent-color-rgb), 0.25);
}

.selected-info {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 10px;
}

.selected-service, .selected-barber {
    margin: 0.5rem 0;
    font-size: 0.95rem;
}

.alert {
    border-radius: 10px;
}

.services-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.service-item {
    position: relative;
}

.service-checkbox {
    display: none;
}

.service-label {
    display: block;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.service-label:hover {
    border-color: var(--accent-color);
    transform: translateY(-2px);
}

.service-checkbox:checked + .service-label {
    border-color: var(--accent-color);
    background-color: rgba(var(--accent-color-rgb), 0.1);
}

.service-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.service-name {
    font-weight: 500;
    font-size: 1.1rem;
}

.service-details {
    display: flex;
    gap: 1rem;
    color: #6c757d;
}

.services-total {
    font-weight: 500;
}

.calendar-strip {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.days-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    text-align: center;
}

.day-name {
    font-size: 0.85rem;
    color: #6c757d;
    padding: 8px;
    font-weight: 500;
}

.day-button {
    padding: 8px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #f8f9fa;
    border: 2px solid transparent;
}

.day-button:hover:not(.disabled) {
    background: #e9ecef;
    transform: translateY(-2px);
}

.day-button.active {
    background: var(--accent-color);
    color: white;
    border-color: var(--accent-color);
}

.day-button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #e9ecef;
}

.day-button.weekend {
    color: var(--accent-color);
}

.day-button.weekend.active {
    color: white;
}

.day-button.weekend:hover:not(.disabled) {
    background: var(--accent-color);
    color: white;
}

.day-button.today {
    border: 2px solid var(--accent-color);
    color: var(--accent-color);
    font-weight: 600;
}

.day-button.today.active {
    color: white;
}

.day-button.today:hover:not(.disabled) {
    background: var(--accent-color);
    color: white;
}

.time-section-title {
    color: #495057;
    font-weight: 600;
}

.time-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 8px;
}

.time-slot {
    padding: 8px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
    color: #495057;
}

.time-slot:hover:not(.disabled) {
    border-color: var(--accent-color);
    transform: translateY(-2px);
}

.time-slot.active {
    background: var(--accent-color);
    color: white;
    border-color: var(--accent-color);
}

.time-slot.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8f9fa;
}

.month-navigation {
    padding: 0.5rem 0;
}

.month-title {
    font-weight: 600;
    color: #495057;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const barberSelect = document.getElementById('barber_filter');
    const dateInput = document.getElementById('appointment_date');
    const timeInput = document.getElementById('appointment_time');
    const continueButton = document.getElementById('continue-to-step2');
    const submitButton = document.getElementById('submit-button');
    const backButton = document.getElementById('back-to-step1');
    const step1Content = document.getElementById('step1-content');
    const step2Content = document.getElementById('step2-content');
    const step1Indicator = document.getElementById('step1');
    const step2Indicator = document.getElementById('step2');
    const selectedServices = document.querySelector('.selected-services');
    const servicesDetails = document.querySelector('.services-details');
    const selectedServicesInfo = document.querySelector('.selected-services-info');
    const totalDuration = document.querySelector('.total-duration');
    const totalPrice = document.querySelector('.total-price');
    const totalDurationStep2 = document.querySelector('.total-duration-step2');
    const totalPriceStep2 = document.querySelector('.total-price-step2');
    const prevMonthBtn = document.querySelector('.prev-month');
    const nextMonthBtn = document.querySelector('.next-month');
    const monthTitle = document.querySelector('.month-title');
    const daysGrid = document.querySelector('.days-grid');
    const timeSlots = document.querySelectorAll('.time-slot');

    let currentDate = new Date();
    let selectedDate = null;
    let selectedTime = null;

    function updateServicesInfo() {
        const selectedCheckboxes = Array.from(serviceCheckboxes).filter(cb => cb.checked);
        
        if (selectedCheckboxes.length > 0) {
            let totalDurationValue = 0;
            let totalPriceValue = 0;
            let servicesHTML = '';

            selectedCheckboxes.forEach(checkbox => {
                const duration = parseInt(checkbox.dataset.duration);
                const price = parseInt(checkbox.dataset.price);
                const name = checkbox.dataset.name;

                totalDurationValue += duration;
                totalPriceValue += price;
                servicesHTML += `
                    <div class="selected-service-item">
                        <span>${name}</span>
                        <div class="service-details">
                            <span>${duration} мин</span>
                            <span>${price}₽</span>
                        </div>
                    </div>
                `;
            });

            servicesDetails.innerHTML = servicesHTML;
            selectedServices.innerHTML = servicesHTML;
            totalDuration.textContent = `${totalDurationValue} мин`;
            totalPrice.textContent = `${totalPriceValue}₽`;
            totalDurationStep2.textContent = `${totalDurationValue} мин`;
            totalPriceStep2.textContent = `${totalPriceValue}₽`;
            selectedServicesInfo.style.display = 'block';
        } else {
            selectedServicesInfo.style.display = 'none';
        }

        updateContinueButton();
    }

    function updateContinueButton() {
        const anyServiceSelected = Array.from(serviceCheckboxes).some(cb => cb.checked);
        continueButton.disabled = !anyServiceSelected;
    }

    function updateSubmitButton() {
        const anyServiceSelected = Array.from(serviceCheckboxes).some(cb => cb.checked);
        const barberSelected = barberSelect.value && barberSelect.value !== 'all';
        const dateSelected = dateInput.value;
        const timeSelected = timeInput.value;
        
        submitButton.disabled = !anyServiceSelected || !barberSelected || !dateSelected || !timeSelected;
    }

    // Добавляем функцию форматирования дня недели
    function formatDayOfWeek(date) {
        const days = {
            0: 'sun',
            1: 'mon',
            2: 'tue',
            3: 'wed',
            4: 'thu',
            5: 'fri',
            6: 'sat'
        };
        return days[date.getDay()];
    }

    function generateCalendar() {
        const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
        const monthNames = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        
        monthTitle.textContent = monthNames[currentDate.getMonth()];
        
        // Очищаем сетку дней, оставляя названия дней недели
        const dayNamesElements = Array.from(daysGrid.querySelectorAll('.day-name'));
        daysGrid.innerHTML = '';
        dayNamesElements.forEach(dayName => daysGrid.appendChild(dayName));

        // Корректируем firstDay для начала недели с понедельника
        let adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1;

        // Добавляем пустые ячейки в начале месяца
        for (let i = 0; i < adjustedFirstDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'day-button disabled';
            daysGrid.appendChild(emptyDay);
        }

        // Получаем текущую дату без времени
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Добавляем дни месяца
        for (let day = 1; day <= daysInMonth; day++) {
            const dayButton = document.createElement('div');
            dayButton.className = 'day-button';
            dayButton.textContent = day;

            const currentDateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            currentDateObj.setHours(0, 0, 0, 0);

            // Если дата в прошлом или это прошедшее время сегодняшнего дня
            if (currentDateObj < today) {
                dayButton.classList.add('disabled');
            } 
            // Проверяем выходные дни
            else {
                const dayOfWeek = formatDayOfWeek(currentDateObj);
                if (dayOfWeek === 'sat' || dayOfWeek === 'sun') {
                    dayButton.classList.add('weekend');
                }
            }
            // Если это сегодняшний день
            if (currentDateObj.getTime() === today.getTime()) {
                dayButton.classList.add('today');
            }

            if (!dayButton.classList.contains('disabled')) {
                dayButton.addEventListener('click', () => selectDate(currentDateObj, dayButton));
            }

            daysGrid.appendChild(dayButton);
        }
    }

    function selectDate(date, button) {
        const prevActive = daysGrid.querySelector('.day-button.active');
        if (prevActive) {
            prevActive.classList.remove('active');
        }

        selectedDate = date;
        button.classList.add('active');
        
        // Форматируем дату для отправки на сервер
        const formattedDate = date.toISOString().split('T')[0];
        dateInput.value = formattedDate;
        
        // Добавляем день недели в data-атрибут для дополнительной проверки
        button.dataset.dayOfWeek = formatDayOfWeek(date);
        
        // Проверяем доступность времени при выборе даты
        checkAvailableTimeSlots();
        updateSubmitButton();
    }

    function checkAvailableTimeSlots() {
        const selectedServices = Array.from(serviceCheckboxes).filter(cb => cb.checked);
        const timeSlotsContainer = document.querySelector('.time-slots-container');
        const noSlotsMessage = document.querySelector('.no-slots-message');
        const timeSections = document.querySelector('.time-sections');

        if (!selectedDate || !barberSelect.value || barberSelect.value === 'all' || selectedServices.length === 0) {
            return;
        }

        // Получаем день недели выбранной даты
        const dayOfWeek = formatDayOfWeek(selectedDate);

        // Делаем все слоты неактивными на время проверки
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.classList.add('disabled');
            slot.disabled = true;
            // Удаляем все обработчики событий
            slot.replaceWith(slot.cloneNode(true));
        });

        // Выводим данные запроса в консоль для отладки
        console.log('Отправляем запрос с параметрами:', {
            date: dateInput.value,
            barber_id: barberSelect.value,
            service_id: selectedServices[0].value,
            day_of_week: dayOfWeek
        });

        // Запрашиваем доступные слоты
        fetch(`/appointments/available-times?date=${dateInput.value}&barber_id=${barberSelect.value}&service_id=${selectedServices[0].value}&day_of_week=${dayOfWeek}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Выводим ответ в консоль для отладки
            console.log('Получен ответ:', data);

            // Если есть ошибка или нет доступных слотов
            if (data.error || !data.available_times || data.available_times.length === 0) {
                noSlotsMessage.style.display = 'block';
                timeSections.style.display = 'none';
                return;
            }

            // Если есть доступные слоты
            noSlotsMessage.style.display = 'none';
            timeSections.style.display = 'block';

            // Сначала делаем все слоты неактивными
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.classList.add('disabled');
                slot.disabled = true;
                // Удаляем все обработчики событий
                slot.replaceWith(slot.cloneNode(true));
            });

            // Активируем доступные слоты
            data.available_times.forEach(time => {
                const slot = document.querySelector(`.time-slot[data-time="${time}"]`);
                if (slot) {
                    slot.classList.remove('disabled');
                    slot.disabled = false;
                    slot.addEventListener('click', () => {
                        // Убираем активный класс у предыдущего выбранного слота
                        const prevActive = document.querySelector('.time-slot.active');
                        if (prevActive) {
                            prevActive.classList.remove('active');
                        }
                        // Добавляем активный класс текущему слоту
                        slot.classList.add('active');
                        timeInput.value = time;
                        updateSubmitButton();
                    });
                }
            });
        })
        .catch(error => {
            console.error('Ошибка при проверке доступного времени:', error);
            noSlotsMessage.style.display = 'block';
            timeSections.style.display = 'none';
        });
    }

    // Инициализация календаря
    generateCalendar();

    // Обработчики событий
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            checkAvailableTimeSlots();
            updateServicesInfo();
        });
    });

    barberSelect.addEventListener('change', checkAvailableTimeSlots);

    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        generateCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        generateCalendar();
    });

    continueButton.addEventListener('click', function() {
        step1Content.classList.remove('active');
        step2Content.classList.add('active');
        step1Indicator.classList.remove('active');
        step2Indicator.classList.add('active');
    });

    backButton.addEventListener('click', function() {
        step2Content.classList.remove('active');
        step1Content.classList.add('active');
        step2Indicator.classList.remove('active');
        step1Indicator.classList.add('active');
    });

    step1Indicator.addEventListener('click', function() {
        if (step2Content.classList.contains('active')) {
            step2Content.classList.remove('active');
            step1Content.classList.add('active');
            step2Indicator.classList.remove('active');
            step1Indicator.classList.add('active');
        }
    });

    step2Indicator.addEventListener('click', function() {
        if (step1Content.classList.contains('active') && continueButton.disabled === false) {
            step1Content.classList.remove('active');
            step2Content.classList.add('active');
            step1Indicator.classList.remove('active');
            step2Indicator.classList.add('active');
        }
    });

    // Обновляем обработку отправки формы
    const appointmentForm = document.getElementById('appointmentForm');
    
    appointmentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const anyServiceSelected = Array.from(serviceCheckboxes).some(cb => cb.checked);
        const barberSelected = barberSelect.value && barberSelect.value !== 'all';
        const dateSelected = dateInput.value;
        const timeSelected = timeInput.value;

        if (!anyServiceSelected || !barberSelected || !dateSelected || !timeSelected) {
            alert('Пожалуйста, заполните все необходимые поля');
            return;
        }

        // Добавляем barber_id в formData
        formData.set('barber_id', barberSelect.value);

        // Отправляем форму через fetch
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                let errorMessage = 'Пожалуйста, исправьте следующие ошибки:\n\n';
                Object.keys(data.errors).forEach(key => {
                    errorMessage += `${data.errors[key].join('\n')}\n`;
                });
                alert(errorMessage);
            } else if (data.message) {
                alert(data.message);
                if (data.success) {
                    window.location.href = '/appointments';
                }
            } else {
                window.location.href = '/appointments';
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при отправке формы. Пожалуйста, попробуйте еще раз.');
        });
    });

    function updateTimeSlots(availableTimes) {
        const timeSlotsContainer = document.querySelector('.time-slots-container');
        const noSlotsMessage = document.querySelector('.no-slots-message');
        const timeSections = document.querySelector('.time-sections');

        if (!availableTimes || availableTimes.length === 0) {
            noSlotsMessage.style.display = 'block';
            timeSections.style.display = 'none';
        } else {
            noSlotsMessage.style.display = 'none';
            timeSections.style.display = 'block';
            // ... existing time slots update code ...
        }
    }
});
</script>
@endsection 