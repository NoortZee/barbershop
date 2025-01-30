@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">GENTLEMEN'S CUT</h1>
                <p class="hero-subtitle">Искусство мужского стиля</p>
                <a href="/appointments/create" class="btn btn-premium">Записаться сейчас</a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Почему выбирают нас</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-cut fa-3x mb-4" style="color: var(--accent-color)"></i>
                            <h4 class="card-title mb-3">Мастерство</h4>
                            <p class="card-text">Наши барберы - признанные мастера своего дела с международными сертификатами</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-clock fa-3x mb-4" style="color: var(--accent-color)"></i>
                            <h4 class="card-title mb-3">Комфорт</h4>
                            <p class="card-text">Удобная онлайн-запись и гибкий график работы для вашего удобства</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-star fa-3x mb-4" style="color: var(--accent-color)"></i>
                            <h4 class="card-title mb-3">Атмосфера</h4>
                            <p class="card-text">Уютное пространство, где каждый клиент чувствует себя особенным</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-light popular-services">
        <div class="container">
            <h2 class="section-title text-center mb-5">Популярные услуги</h2>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="service-card">
                        <div class="service-card-image">
                            <img src="/images/service-1.jpg" alt="Стрижка" class="img-fluid">
                            <div class="service-overlay">
                                <a href="/services" class="btn-details">Подробнее</a>
                            </div>
                        </div>
                        <div class="service-card-content">
                            <h4>Премиум стрижка</h4>
                            <p>Индивидуальный подход к созданию вашего стиля с учетом особенностей внешности и пожеланий</p>
                            <div class="service-footer">
                                <span class="price">от 3000 ₽</span>
                                <a href="/appointments/create" class="btn-book">Записаться</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-card">
                        <div class="service-card-image">
                            <img src="/images/service-2.jpg" alt="Борода" class="img-fluid">
                            <div class="service-overlay">
                                <a href="/services" class="btn-details">Подробнее</a>
                            </div>
                        </div>
                        <div class="service-card-content">
                            <h4>Моделирование бороды</h4>
                            <p>Профессиональное оформление бороды с использованием премиальной косметики</p>
                            <div class="service-footer">
                                <span class="price">от 2000 ₽</span>
                                <a href="/appointments/create" class="btn-book">Записаться</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-card">
                        <div class="service-card-image">
                            <img src="/images/service-3.jpg" alt="Комплекс" class="img-fluid">
                            <div class="service-overlay">
                                <a href="/services" class="btn-details">Подробнее</a>
                            </div>
                        </div>
                        <div class="service-card-content">
                            <h4>VIP-комплекс</h4>
                            <p>Полный комплекс услуг для создания безупречного образа современного джентльмена</p>
                            <div class="service-footer">
                                <span class="price">от 5000 ₽</span>
                                <a href="/appointments/create" class="btn-book">Записаться</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Наши мастера</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100">
                        <img src="/images/barber-1.jpg" class="card-img-top" alt="Барбер">
                        <div class="card-body text-center">
                            <h4 class="card-title">Александр</h4>
                            <p class="text-muted">Топ-барбер</p>
                            <p class="card-text">10 лет опыта в создании безупречных образов</p>
                            <a href="/barbers" class="btn btn-premium mt-3">Подробнее</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100">
                        <img src="/images/barber-2.jpg" class="card-img-top" alt="Барбер">
                        <div class="card-body text-center">
                            <h4 class="card-title">Максим</h4>
                            <p class="text-muted">Стилист-барбер</p>
                            <p class="card-text">Специалист по сложным техникам стрижки</p>
                            <a href="/barbers" class="btn btn-premium mt-3">Подробнее</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100">
                        <img src="/images/barber-3.jpg" class="card-img-top" alt="Барбер">
                        <div class="card-body text-center">
                            <h4 class="card-title">Дмитрий</h4>
                            <p class="text-muted">Мастер-барбер</p>
                            <p class="card-text">Эксперт по моделированию бороды</p>
                            <a href="/barbers" class="btn btn-premium mt-3">Подробнее</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100">
                        <img src="/images/barber-4.jpg" class="card-img-top" alt="Барбер">
                        <div class="card-body text-center">
                            <h4 class="card-title">Артём</h4>
                            <p class="text-muted">Креативный барбер</p>
                            <p class="card-text">Создатель трендовых образов</p>
                            <a href="/barbers" class="btn btn-premium mt-3">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-dark text-white" style="background: linear-gradient(rgba(0,0,0,0.9), rgba(0,0,0,0.9)), url('/images/cta-bg.jpg'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 text-center text-lg-start">
                    <h2 class="mb-4">Готовы к преображению?</h2>
                    <p class="lead mb-0">Запишитесь прямо сейчас и получите скидку 10% на первое посещение</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                    <a href="/appointments/create" class="btn btn-premium btn-lg">Записаться</a>
                </div>
            </div>
        </div>
    </section>

    <style>
    .popular-services {
        padding: 4rem 0;
        background: linear-gradient(to bottom, var(--light-bg) 0%, #ffffff 100%);
    }

    .service-card {
        height: 100%;
        background: white;
        border-radius: 15px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--card-shadow-hover);
    }

    .service-card-image {
        position: relative;
        overflow: hidden;
        height: 250px;
        border-radius: 15px 15px 0 0;
    }

    .service-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .service-card:hover .service-card-image img {
        transform: scale(1.1);
    }

    .service-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--transition);
    }

    .service-card:hover .service-overlay {
        opacity: 1;
    }

    .btn-details {
        color: #fff;
        text-decoration: none;
        padding: 12px 30px;
        border: 2px solid #fff;
        border-radius: 30px;
        font-weight: 600;
        transition: var(--transition);
        background-color: rgba(255, 255, 255, 0.1);
    }

    .btn-details:hover {
        background-color: #fff;
        color: var(--accent-color);
        transform: translateY(-2px);
    }

    .service-card-content {
        padding: 2rem;
        background: white;
        border-radius: 0 0 15px 15px;
    }

    .service-card-content h4 {
        color: var(--primary-color);
        font-family: 'Playfair Display', serif;
        margin-bottom: 1rem;
    }

    .service-card-content p {
        color: var(--text-color);
        margin-bottom: 1.5rem;
        opacity: 0.8;
    }

    .service-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid rgba(0,0,0,0.1);
    }

    .price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--accent-color);
    }

    .btn-book {
        background: var(--accent-color);
        color: #fff;
        text-decoration: none;
        padding: 10px 25px;
        border-radius: 30px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-block;
    }

    .btn-book:hover {
        background: var(--accent-color-dark);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(197, 164, 126, 0.3);
    }

    .section-title {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
        font-size: 2.5rem;
        color: var(--primary-color);
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

    .hero-section {
        position: relative;
        padding: 8rem 0;
        margin-top: -90px;
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/images/hero-bg.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: white;
    }

    .hero-content {
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }

    .hero-title {
        font-size: 4.5rem;
        margin-bottom: 2rem;
        animation: fadeInDown 1s ease-out;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 1.8rem;
        margin-bottom: 3rem;
        opacity: 0.9;
        animation: fadeInUp 1s ease-out;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    .section {
        padding: 4rem 0;
    }

    .feature-icon {
        font-size: 3rem;
        color: var(--accent-color);
        margin-bottom: 1.5rem;
        transition: var(--transition);
    }

    .card:hover .feature-icon {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 3rem;
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
        }
        
        .section {
            padding: 3rem 0;
        }
    }
    </style>
@endsection 