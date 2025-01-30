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

        <div class="row g-4">
            @forelse($reviews as $review)
                <div class="col-md-6">
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-author">
                                <div class="author-avatar">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div class="author-info">
                                    <h4>{{ $review->user->name }}</h4>
                                    <div class="review-date">{{ $review->created_at->format('d.m.Y') }}</div>
                                </div>
                            </div>
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="review-content">
                            <p>{{ $review->comment }}</p>
                        </div>
                        @auth
                            @if(auth()->user()->id === $review->user_id)
                                <div class="review-actions">
                                    <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Редактировать
                                    </a>
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                            <i class="fas fa-trash"></i> Удалить
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Пока нет отзывов. Будьте первым!
                    </div>
                </div>
            @endforelse
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
</style>
@endsection 