document.querySelectorAll('.slider-container').forEach(container => {
    const slider = container.querySelector('.slider');
    const slides = slider?.querySelectorAll('img');
    
    // Прерываем выполнение, если слайдера нет или слайдов меньше двух
    if (!slider || !slides || slides.length < 2) return;

    const prevBtn = container.querySelector('.prev-btn');
    const nextBtn = container.querySelector('.next-btn');
    const dotsContainer = container.querySelector('.slider-dots');
    
    let slideIndex = 0;

    // Единая функция для обновления всего состояния слайдера
    const goToSlide = (index) => {
        // Ограничиваем индекс, чтобы он не выходил за пределы количества слайдов
        slideIndex = Math.max(0, Math.min(index, slides.length - 1));
        
        slider.style.transform = `translateX(-${slideIndex * 100}%)`;
        
        if (prevBtn) prevBtn.disabled = slideIndex === 0;
        if (nextBtn) nextBtn.disabled = slideIndex === slides.length - 1;
        
        // Обновляем активную точку, если контейнер существует
        dotsContainer?.querySelectorAll('.dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === slideIndex);
        });
    };

    // Генерация точек
    if (dotsContainer) {
        slides.forEach((_, i) => {
            const dot = document.createElement('div');
            dot.className = `dot ${i === 0 ? 'active' : ''}`;
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.append(dot);
        });
    }

    // Обработчики кликов по кнопкам (используем опциональную цепочку ?.)
    prevBtn?.addEventListener('click', () => goToSlide(slideIndex - 1));
    nextBtn?.addEventListener('click', () => goToSlide(slideIndex + 1));

    // Обработчик клавиатуры
    container.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') goToSlide(slideIndex - 1);
        if (e.key === 'ArrowRight') goToSlide(slideIndex + 1);
    });

    // Инициализация состояния кнопок
    goToSlide(0);
});