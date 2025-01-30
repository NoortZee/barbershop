<!DOCTYPE html>
<html lang="ru" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Барбершоп - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
            animation: slideUp 0.5s ease-out;
        }
        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .footer-content {
            transition: all 0.3s ease;
        }
        .footer-content:hover {
            transform: translateY(-5px);
        }
        .contact-info {
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        .contact-info:hover {
            opacity: 1;
        }
        .copyright {
            padding: 1rem 0;
            background-color: rgba(0, 0, 0, 0.2);
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .copyright a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .copyright a:hover {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Барбершоп</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/services">Услуги</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/barbers">Барберы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/appointments/create">Записаться</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        @yield('content')
    </main>

    <footer class="bg-dark text-white">
        <div class="container py-4">
            <div class="row footer-content">
                <div class="col-md-6">
                    <h5>Барбершоп</h5>
                    <p>Мы делаем вас красивее</p>
                </div>
                <div class="col-md-6 text-end contact-info">
                    <p>Телефон: +7 (999) 999-99-99</p>
                    <p>Email: info@barbershop.ru</p>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} Барбершоп. Все права защищены.</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="mb-0">
                            Разработано с <span class="text-danger">&hearts;</span> для вашей красоты
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
