@forelse($reviews as $review)
    <div class="col-md-6 review-item">
        <div class="review-card">
            <div class="review-header">
                <div class="review-author">
                    <div class="author-avatar">
                        {{ substr($review->user->name, 0, 1) }}
                    </div>
                    <div class="author-info">
                        <h4>{{ $review->user->name }}</h4>
                        <div class="review-date">{{ $review->created_at->format('d.m.Y') }}</div>
                        <div class="barber-name text-muted">Мастер: {{ $review->barber->name }}</div>
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

@if($reviews->hasPages())
    <div class="col-12 mt-4 text-center">
        {{ $reviews->links() }}
    </div>
@endif 