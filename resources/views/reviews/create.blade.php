@extends('layouts.app')

@section('title', 'Оставить отзыв')

@section('content')
<div class="review-form-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="review-form-card">
                    <h2 class="text-center mb-4">Оставить отзыв</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reviews.store') }}" method="POST" class="review-form">
                        @csrf

                        <div class="form-group mb-4">
                            <label class="form-label">Оценка</label>
                            <div class="rating-input">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                                @endfor
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="comment" class="form-label">Ваш отзыв</label>
                            <textarea class="form-control" id="comment" name="comment" rows="5" required minlength="10">{{ old('comment') }}</textarea>
                            <div class="form-text">Минимум 10 символов</div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-premium">Опубликовать отзыв</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.review-form-section {
    padding: 4rem 0;
    background: linear-gradient(to bottom, var(--light-bg) 0%, #ffffff 100%);
}

.review-form-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: var(--card-shadow);
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 0.5rem;
}

.rating-input input {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #e4e4e4;
    transition: var(--transition);
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input:checked ~ label {
    color: #ffd700;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 0.75rem;
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--accent-color-rgb), 0.25);
}

.btn-premium {
    background-color: var(--accent-color);
    color: white;
    padding: 0.75rem 2rem;
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