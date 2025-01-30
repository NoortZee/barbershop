@extends('layouts.app')

@section('title', 'Наши барберы')

@section('content')
<section class="barbers-hero" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/images/barbers-bg.jpg'); background-size: cover; background-position: center; padding: 150px 0 100px;">
    <div class="container text-center text-white">
        <h1 class="display-4 mb-4 fade-in-up">Наши мастера</h1>
        <p class="lead fade-in-up">Профессионалы, которые создают ваш безупречный стиль</p>
    </div>
</section>

<section class="barbers-section py-5">
    <div class="container">
        <div class="row g-4">
            @foreach($barbers as $barber)
                <div class="col-lg-4 col-md-6 fade-in-up">
                    <div class="barber-card">
                        @if($barber->photo)
                            <div class="barber-image">
                                <img src="{{ asset('storage/' . $barber->photo) }}" alt="{{ $barber->name }}" class="img-fluid">
                                <div class="barber-overlay">
                                    <a href="/appointments/create?barber_id={{ $barber->id }}" class="btn btn-premium">Записаться</a>
                                </div>
                            </div>
                        @endif
                        <div class="barber-info text-center p-4">
                            <h3 class="barber-name">{{ $barber->name }}</h3>
                            <p class="barber-position text-muted">Мастер-барбер</p>
                            <p class="barber-experience">Опыт работы: {{ $barber->experience }} лет</p>
                            <p class="barber-description">{{ $barber->description }}</p>
                            <div class="barber-social mt-3">
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-vk"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
.barbers-hero {
    margin-top: -56px;
}

.barber-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
}

.barber-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--card-shadow-hover);
}

.barber-image {
    position: relative;
    overflow: hidden;
}

.barber-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    transition: var(--transition);
}

.barber-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition);
}

.barber-card:hover .barber-overlay {
    opacity: 1;
}

.barber-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.barber-position {
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 1rem;
}

.barber-experience {
    color: var(--accent-color);
    font-weight: 600;
    margin-bottom: 1rem;
}

.barber-description {
    color: var(--text-color);
    font-size: 0.95rem;
    line-height: 1.6;
}

.barber-social {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.social-link {
    color: var(--primary-color);
    font-size: 1.2rem;
    transition: var(--transition);
}

.social-link:hover {
    color: var(--accent-color);
}

.btn-premium {
    background-color: var(--accent-color);
    color: white;
    padding: 0.8rem 2rem;
    border-radius: 30px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 1px;
    transition: var(--transition);
    border: none;
}

.btn-premium:hover {
    background-color: var(--accent-color-dark);
    color: white;
    transform: translateY(-2px);
}
</style>
@endsection 