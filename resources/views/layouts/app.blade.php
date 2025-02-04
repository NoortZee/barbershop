<!DOCTYPE html>
<html lang="ru" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a1a1a;
            --accent-color: #c5a47e;
            --accent-color-dark: #b08f68;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --card-shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.2);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            color: var(--text-color);
            background-color: var(--light-bg);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        .navbar {
            background-color: var(--primary-color) !important;
            padding: 1.5rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--accent-color) !important;
            letter-spacing: 1px;
        }

        .nav-link {
            font-size: 1.1rem;
            font-weight: 400;
            margin: 0 1rem;
            position: relative;
            color: #fff !important;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--accent-color);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-btn {
            background-color: var(--accent-color);
            color: white !important;
            padding: 10px 25px !important;
            border-radius: 30px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin-left: 15px;
        }

        .nav-btn:hover {
            background-color: var(--accent-color-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(197, 164, 126, 0.3);
        }

        /* Стили для выпадающего меню пользователя */
        .dropdown-toggle::after {
            display: none;
        }

        .nav-item.dropdown .nav-link {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #fff !important;
        }

        .dropdown-menu {
            background-color: var(--primary-color);
            border: 1px solid var(--accent-color);
            border-radius: 10px;
            margin-top: 10px;
            padding: 0.5rem 0;
        }

        .dropdown-item {
            color: #fff;
            padding: 0.7rem 1.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--accent-color);
            color: #fff;
        }

        main {
            flex: 1 0 auto;
            padding: 3rem 0;
            margin-top: 80px;
        }

        .hero-section {
            position: relative;
            padding: 8rem 0;
            margin-top: -90px;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
        }

        .hero-content {
            text-align: center;
            padding: 4rem 0;
        }

        .hero-title {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: fadeInDown 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            animation: fadeInUp 1s ease-out;
        }

        .btn {
            border-radius: 30px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: var(--transition);
        }

        .btn-premium {
            background-color: var(--accent-color);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(197, 164, 126, 0.2);
        }

        .btn-premium:hover, .btn-premium:focus {
            background-color: var(--accent-color-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(197, 164, 126, 0.4);
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
        }

        .btn-outline:hover, .btn-outline:focus {
            background-color: var(--accent-color);
            color: white;
            transform: translateY(-2px);
        }

        .section {
            padding: 4rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--accent-color);
        }

        .card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: var(--card-shadow-hover);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            color: var(--primary-color);
            font-family: 'Playfair Display', serif;
            margin-bottom: 1rem;
        }

        .card-text {
            color: var(--text-color);
            font-size: 1rem;
            line-height: 1.6;
        }

        .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: var(--transition);
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            padding-top: 3rem;
        }

        .footer-content {
            margin-bottom: 2rem;
        }

        .footer-title {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent-color);
        }

        .social-links {
            margin-top: 2rem;
        }

        .social-links a {
            color: white;
            font-size: 1.5rem;
            margin-right: 1.5rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: var(--accent-color);
            transform: translateY(-3px);
        }

        .copyright {
            padding: 1.5rem 0;
            background-color: rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .fade-in-down {
            animation: fadeInDown 0.6s ease-out;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">GENTLEMEN'S CUT</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/services">Услуги</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/barbers">Мастера</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reviews.index') }}">Отзывы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-btn" href="/appointments/create">ЗАПИСАТЬСЯ</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-user me-1"></i>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('appointments.index') }}">
                                    <i class="fas fa-calendar-check me-2"></i>Мои записи
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Профиль
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>{{ __('Выход') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="row footer-content">
                <div class="col-lg-4">
                    <h5 class="footer-title">GENTLEMEN'S CUT</h5>
                    <p>Премиальный барбершоп для настоящих джентльменов. Место, где традиции встречаются с современностью.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-vk"></i></a>
                        <a href="#"><i class="fab fa-telegram"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Контакты</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Москва, ул. Премиальная, 1</li>
                        <li><i class="fas fa-phone me-2"></i> +7 (999) 999-99-99</li>
                        <li><i class="fas fa-envelope me-2"></i> info@gentlemenscut.ru</li>
                        <li><i class="fas fa-clock me-2"></i> Пн-Вс: 10:00 - 22:00</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Навигация</h5>
                    <ul class="footer-links">
                        <li><a href="/about">О нас</a></li>
                        <li><a href="/services">Услуги</a></li>
                        <li><a href="/barbers">Мастера</a></li>
                        <li><a href="/gallery">Галерея работ</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <p class="mb-0">&copy; {{ date('Y') }} GENTLEMEN'S CUT. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(window).scroll(function() {
            if ($(window).scrollTop() > 50) {
                $('.navbar').addClass('shadow-sm').css('padding', '1rem 0');
            } else {
                $('.navbar').removeClass('shadow-sm').css('padding', '1.5rem 0');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
