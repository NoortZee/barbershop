@extends('layouts.app')

@section('title', 'Услуги')

@section('content')
    <div class="services-section">
        <div class="container">
            <h1 class="section-title text-center mb-5">Наши услуги</h1>

            <div class="row g-4">
                @foreach($services as $service)
                    <div class="col-md-4">
                        <div class="card service-card fade-in-up">
                            <div class="card-body">
                                <div class="service-icon">
                                    <i class="fas fa-cut"></i>
                                </div>
                                <h3 class="card-title">{{ $service->name }}</h3>
                                <p class="card-text">{{ $service->description }}</p>
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
                                <a href="/appointments/create?service_id={{ $service->id }}" class="btn btn-premium w-100">Записаться</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .services-section {
            padding: 4rem 0;
            background: linear-gradient(to bottom, var(--light-bg) 0%, #ffffff 100%);
        }

        .service-icon {
            width: 60px;
            height: 60px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: var(--transition);
        }

        .service-card:hover .service-icon {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(197, 164, 126, 0.3);
        }

        .service-icon i {
            font-size: 2rem;
            color: #fff;
        }

        .service-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            padding: 0.75rem 0;
            border-top: 1px solid rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(0,0,0,0.1);
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
            color: var(--text-color);
            opacity: 0.8;
        }

        .service-duration {
            display: flex;
            align-items: center;
            color: var(--text-color);
            opacity: 0.8;
        }

        .service-duration i {
            margin-right: 0.5rem;
        }

        .service-card {
            animation-delay: calc(var(--animation-order) * 0.1s);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .service-card .card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .service-card .card-text {
            flex-grow: 1;
            margin-bottom: 1rem;
        }
    </style>
@endsection 