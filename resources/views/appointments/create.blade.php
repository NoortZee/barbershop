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

                    <form action="{{ route('appointments.store') }}" method="POST" id="appointmentForm">
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
                                                   name="service_ids[]" 
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
                                            <option value="all">Все доступные</option>
                                            @foreach($barbers as $barber)
                                                <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="calendar-strip mb-4">
                                    <div class="month-title mb-2">Январь-февраль</div>
                                    <div class="days-strip">
                                        <button type="button" class="btn-nav prev">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <div class="days-scroll" id="daysStrip">
                                            <!-- Дни будут добавлены через JavaScript -->
                                        </div>
                                        <button type="button" class="btn-nav next">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>

                                <input type="hidden" name="appointment_date" id="appointment_date" required>
                                <input type="hidden" name="appointment_time" id="appointment_time" required>

                                <div class="time-slots" id="timeSlots" style="display: none;">
                                    <div class="time-slots-section">
                                        <h6 class="time-section-title">Утро</h6>
                                        <div class="time-slots-grid morning-slots">
                                            <!-- Слоты времени будут добавлены через JavaScript -->
                                        </div>
                                    </div>
                                    <div class="time-slots-section">
                                        <h6 class="time-section-title">День</h6>
                                        <div class="time-slots-grid day-slots">
                                            <!-- Слоты времени будут добавлены через JavaScript -->
                                        </div>
                                    </div>
                                    <div class="time-slots-section">
                                        <h6 class="time-section-title">Вечер</h6>
                                        <div class="time-slots-grid evening-slots">
                                            <!-- Слоты времени будут добавлены через JavaScript -->
                                        </div>
                                    </div>
                                </div>
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
    border-radius: 10px;
    padding: 1rem;
}

.month-title {
    font-weight: 500;
    color: #333;
}

.days-strip {
    position: relative;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-nav {
    background: none;
    border: none;
    font-size: 1.2rem;
    padding: 0.5rem;
    color: #333;
    cursor: pointer;
    z-index: 2;
}

.days-scroll {
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
    scroll-behavior: smooth;
    -ms-overflow-style: none;
    scrollbar-width: none;
    padding: 0.5rem 0;
}

.days-scroll::-webkit-scrollbar {
    display: none;
}

.day-btn {
    min-width: 60px;
    padding: 0.5rem;
    border: none;
    border-radius: 10px;
    background: #f8f9fa;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: all 0.2s;
}

.day-btn .weekday {
    font-size: 0.8rem;
    color: #666;
    text-transform: lowercase;
}

.day-btn .date {
    font-size: 1.1rem;
    font-weight: 500;
}

.day-btn:hover:not(.disabled) {
    background: #e9ecef;
    transform: translateY(-2px);
}

.day-btn.active {
    background: var(--accent-color);
    color: white;
}

.day-btn.active .weekday {
    color: rgba(255, 255, 255, 0.8);
}

.day-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8f9fa;
}

.time-slots {
    margin-top: 1.5rem;
}

.time-slots-section {
    margin-bottom: 1.5rem;
}

.time-section-title {
    margin-bottom: 1rem;
    color: #666;
}

.time-slots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 0.5rem;
}

.time-slot {
    padding: 0.75rem;
    text-align: center;
    background: #f8f9fa;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.time-slot:hover:not(.disabled) {
    background: #e9ecef;
    transform: translateY(-2px);
}

.time-slot.active {
    background: var(--accent-color);
    color: white;
}

.time-slot.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const barberSelect = document.getElementById('barber_filter');
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time');
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
    const timeSlots = document.getElementById('timeSlots');
    const morningSlots = document.querySelector('.morning-slots');
    const daySlots = document.querySelector('.day-slots');
    const eveningSlots = document.querySelector('.evening-slots');
    const daysStrip = document.getElementById('daysStrip');
    const prevButton = document.querySelector('.btn-nav.prev');
    const nextButton = document.querySelector('.btn-nav.next');

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
        submitButton.disabled = !barberSelect.value || !dateInput.value || !timeSelect.value;
    }

    function generateDays() {
        daysStrip.innerHTML = '';
        const today = new Date();
        
        // Генерируем дни на неделю вперед
        for(let i = 0; i < 14; i++) {
            const date = new Date(currentDate);
            date.setDate(date.getDate() + i);
            
            const dayBtn = document.createElement('button');
            dayBtn.type = 'button';
            dayBtn.className = 'day-btn';
            if (date < today) {
                dayBtn.classList.add('disabled');
            }
            if (selectedDate && date.toDateString() === selectedDate.toDateString()) {
                dayBtn.classList.add('active');
            }

            const weekday = date.toLocaleDateString('ru-RU', { weekday: 'short' });
            const dayNum = date.getDate();

            dayBtn.innerHTML = `
                <span class="weekday">${weekday}</span>
                <span class="date">${dayNum}</span>
            `;

            if (!dayBtn.classList.contains('disabled')) {
                dayBtn.addEventListener('click', () => selectDate(date, dayBtn));
            }

            daysStrip.appendChild(dayBtn);
        }
    }

    function selectDate(date, button) {
        const prevActive = document.querySelector('.day-btn.active');
        if (prevActive) {
            prevActive.classList.remove('active');
        }

        selectedDate = date;
        button.classList.add('active');

        // Обновляем скрытое поле с датой
        const formattedDate = date.toISOString().split('T')[0];
        document.getElementById('appointment_date').value = formattedDate;

        // Загружаем доступное время
        if (barberSelect.value) {
            updateAvailableTimes();
        }
    }

    function updateAvailableTimes() {
        const barberId = barberSelect.value;
        const selectedServices = Array.from(serviceCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (!barberId || selectedServices.length === 0 || !selectedDate) return;

        const formattedDate = selectedDate.toISOString().split('T')[0];
        const queryParams = new URLSearchParams({
            barber_id: barberId,
            date: formattedDate,
            service_ids: selectedServices
        });

        timeSlots.style.display = 'none';
        fetch(`/appointments/available-times?${queryParams}`)
            .then(response => response.json())
            .then(data => {
                morningSlots.innerHTML = '';
                daySlots.innerHTML = '';
                eveningSlots.innerHTML = '';

                data.available_times.forEach(time => {
                    const hour = parseInt(time.split(':')[0]);
                    const timeSlot = document.createElement('button');
                    timeSlot.type = 'button';
                    timeSlot.className = 'time-slot';
                    timeSlot.textContent = time;
                    
                    timeSlot.addEventListener('click', () => selectTime(time, timeSlot));

                    if (hour < 12) {
                        morningSlots.appendChild(timeSlot);
                    } else if (hour < 17) {
                        daySlots.appendChild(timeSlot);
                    } else {
                        eveningSlots.appendChild(timeSlot);
                    }
                });

                timeSlots.style.display = 'block';
                updateSubmitButton();
            });
    }

    function selectTime(time, element) {
        // Убираем активный класс у предыдущего выбранного времени
        const previousActive = document.querySelector('.time-slot.active');
        if (previousActive) {
            previousActive.classList.remove('active');
        }

        // Добавляем активный класс новому времени
        element.classList.add('active');
        selectedTime = time;
        document.getElementById('appointment_time').value = time;
        updateSubmitButton();
    }

    // Обработчики для кнопок навигации
    prevButton.addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() - 7);
        generateDays();
    });

    nextButton.addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() + 7);
        generateDays();
    });

    // Инициализация календаря
    generateDays();

    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateServicesInfo);
    });

    barberSelect.addEventListener('change', updateAvailableTimes);
    dateInput.addEventListener('change', updateAvailableTimes);
    timeSelect.addEventListener('change', updateSubmitButton);

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

    // Добавляем обработчики для кликабельных шагов
    step1Indicator.addEventListener('click', function() {
        if (step2Content.classList.contains('active')) {
            step2Content.classList.remove('active');
            step1Content.classList.add('active');
            step2Indicator.classList.remove('active');
            step1Indicator.classList.add('active');
        }
    });

    step2Indicator.addEventListener('click', function() {
        if (step1Content.classList.contains('active')) {
            step1Content.classList.remove('active');
            step2Content.classList.add('active');
            step1Indicator.classList.remove('active');
            step2Indicator.classList.add('active');
        }
    });
});
</script>
@endsection 