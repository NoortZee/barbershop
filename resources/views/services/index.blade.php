@extends('layouts.app')

@section('title', 'Услуги')

@section('content')
<div class="services-section">
    <h1 class="text-center mb-5">Наши услуги</h1>

    <div class="row g-4">
        @foreach($services as $service)
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-card-inner">
                        <div class="service-icon">
                            <i class="fas fa-cut"></i>
                        </div>
                        <h3 class="service-title">{{ $service->name }}</h3>
                        <p class="service-description">{{ $service->description }}</p>
                        <div class="service-details">
                            <div class="service-price">
                                <span class="amount">{{ $service->price }}</span>
                                <span class="currency">₽</span>
                            </div>
                            <div class="service-duration">
                                <i class="far fa-clock"></i>
                                <span>{{ $service->duration }} мин.</span>
                            </div>
                        </div>
                        <a href="/appointments/create?service_id={{ $service->id }}" class="btn-book">Записаться</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
.services-section {
    padding: 4rem 0;
}

.service-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.service-card-inner {
    padding: 2rem;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.service-icon {
    width: 70px;
    height: 70px;
    background: var(--accent-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.service-icon i {
    font-size: 2rem;
    color: #fff;
}

.service-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2d3436;
}

.service-description {
    color: #636e72;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}

.service-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1rem 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.service-price {
    display: flex;
    align-items: baseline;
}

.service-price .amount {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--accent-color);
}

.service-price .currency {
    font-size: 1.2rem;
    margin-left: 0.2rem;
    color: #636e72;
}

.service-duration {
    display: flex;
    align-items: center;
    color: #636e72;
}

.service-duration i {
    margin-right: 0.5rem;
}

.btn-book {
    display: inline-block;
    padding: 0.8rem 2rem;
    background: var(--accent-color);
    color: #fff;
    text-decoration: none;
    border-radius: 30px;
    text-align: center;
    transition: all 0.3s ease;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.btn-book:hover {
    background: var(--accent-color-dark);
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}
</style>
@endsection 