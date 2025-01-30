@extends('layouts.app')

@section('content')
<div class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-card">
                    <h2 class="auth-title text-center mb-4">{{ __('Вход') }}</h2>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">{{ __('Пароль') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Запомнить меня') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-premium">
                                {{ __('Войти') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-center" href="{{ route('password.request') }}">
                                    {{ __('Забыли пароль?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-section {
    padding: 5rem 0;
    background-color: #f8f9fa;
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
}

.auth-card {
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: var(--card-shadow);
}

.auth-title {
    color: var(--accent-color);
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.form-control {
    padding: 0.8rem 1.2rem;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.form-control:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--accent-color-rgb), 0.25);
}

.btn-premium {
    padding: 0.8rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-link {
    color: var(--accent-color);
    text-decoration: none;
}

.btn-link:hover {
    color: var(--accent-color-dark);
    text-decoration: underline;
}
</style>
@endsection 