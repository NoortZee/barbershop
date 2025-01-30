@extends('layouts.app')

@section('title', 'Отзывы')

@section('content')
<div class="reviews-section">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="section-title">Отзывы наших клиентов</h1>
            </div>
            @auth
            <div class="col-md-4 text-end">
                <a href="{{ route('reviews.create') }}" class="btn btn-premium">Оставить отзыв</a>
            </div>
            @else
            <div class="col-md-4 text-end">
                <a href="{{ route('login') }}" class="btn btn-premium">Войдите, чтобы оставить отзыв</a>
            </div>
            @endauth
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="rating-filter">
                    <span class="me-3">Фильтр по оценке:</span>
                    <div class="btn-group">
                        <a href="{{ request()->url() }}" class="btn {{ !request('rating') ? 'btn-premium' : 'btn-outline-secondary' }}">Все</a>
                        @foreach(range(5, 1) as $rating)
                            <a href="{{ request()->url() }}?rating={{ $rating }}" 
                               class="btn {{ request('rating') == $rating ? 'btn-premium' : 'btn-outline-secondary' }}">
                                {{ $rating }} <i class="fas fa-star"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @include('reviews.partials.reviews-list')
        </div>
    </div>
</div>

<style>
.reviews-section {
    padding: 4rem 0;
    background: linear-gradient(to bottom, var(--light-bg) 0%, #ffffff 100%);
}

.review-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    height: 100%;
}

.review-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--card-shadow-hover);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.review-author {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 50px;
    height: 50px;
    background: var(--accent-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
}

.author-info h4 {
    margin: 0;
    font-size: 1.1rem;
    color: var(--primary-color);
}

.review-date {
    font-size: 0.9rem;
    color: #666;
}

.review-rating {
    color: #ffd700;
}

.review-rating .fa-star {
    margin-left: 2px;
}

.review-rating .fa-star.active {
    color: #ffd700;
}

.review-rating .fa-star:not(.active) {
    color: #e4e4e4;
}

.review-content {
    margin-bottom: 1.5rem;
    color: var(--text-color);
    line-height: 1.6;
}

.review-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(0,0,0,0.1);
}

.rating-filter {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
}

.rating-filter .btn-group {
    border-radius: 30px;
    overflow: hidden;
}

.rating-filter .btn {
    padding: 0.5rem 1rem;
    border: none;
}

.rating-filter .btn:not(:last-child) {
    border-right: 1px solid rgba(0,0,0,0.1);
}

.btn-premium {
    background-color: var(--accent-color);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 30px;
    font-weight: 600;
    transition: var(--transition);
    border: none;
}

.btn-premium:hover {
    background-color: var(--accent-color-dark);
    color: white;
    transform: translateY(-2px);
}

.btn-outline-secondary {
    background-color: transparent;
    border: 1px solid #ddd;
    color: #666;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    color: #333;
}
</style>
@endsection 