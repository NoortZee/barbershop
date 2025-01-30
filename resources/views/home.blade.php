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
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-cut fa-3x mb-4" style="color: var(--accent-color)"></i>
                        <h4 class="card-title mb-3">Мастерство</h4>
                        <p class="card-text">Наши барберы - признанные мастера своего дела с международными сертификатами</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-gem fa-3x mb-4" style="color: var(--accent-color)"></i>
                        <h4 class="card-title mb-3">Премиум-сервис</h4>
                        <p class="card-text">Индивидуальный подход и внимание к каждой детали вашего образа</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x mb-4" style="color: var(--accent-color)"></i>
                        <h4 class="card-title mb-3">Комфорт</h4>
                        <p class="card-text">Удобная онлайн-запись и гибкий график работы для вашего удобства</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
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
    padding: 6rem 0;
    background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
}

.service-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.service-card-image {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.service-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
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
    transition: all 0.3s ease;
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
    transition: all 0.3s ease;
    transform: translateY(20px);
}

.service-card:hover .btn-details {
    transform: translateY(0);
}

.btn-details:hover {
    background: #fff;
    color: var(--accent-color);
}

.service-card-content {
    padding: 2rem;
}

.service-card-content h4 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-size: 1.5rem;
    font-weight: 700;
}

.service-card-content p {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.service-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

.price {
    font-size: 1.25rem;
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
    transition: all 0.3s ease;
}

.btn-book:hover {
    background: var(--primary-color);
    color: #fff;
    transform: translateX(5px);
}

.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 15px;
    margin-bottom: 30px;
}

.section-title::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: var(--accent-color);
}
</style>
@endsection 