// Add progressive-motion support only after JavaScript is available.
document.documentElement.classList.add('js');

// Tab switching for class-view page
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        btn.classList.add('active');
        const tabId = btn.dataset.tab;
        const tabContent = document.getElementById(tabId);
        if (tabContent) {
            tabContent.classList.add('active');
        }
    });
});

// Reveal page sections and cards as they enter the viewport.
const revealTargets = document.querySelectorAll(
    '.section-header, .course-card, .stat, .educator-card, .dashboard-grid .card, ' +
    '.class-card, .auth-box, .table, .page-header, .quick-actions, .content-item, .list'
);

revealTargets.forEach((target, index) => {
    target.classList.add('reveal');

    const siblings = target.parentElement
        ? Array.from(target.parentElement.children).filter(child => child.classList.contains(target.classList[0]))
        : [];
    const siblingIndex = siblings.indexOf(target);
    if (siblingIndex >= 0) {
        target.style.setProperty('--reveal-delay', `${Math.min(siblingIndex * 70, 350)}ms`);
    }
});

if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    revealTargets.forEach(target => revealObserver.observe(target));
} else {
    revealTargets.forEach(target => target.classList.add('is-visible'));
}

// Animate stats once when the stats bar is visible.
const statNumbers = document.querySelectorAll('.stat h3');

function animateNumber(element) {
    const match = element.textContent.trim().match(/^(\d+)(.*)$/);
    if (!match) return;

    const target = Number(match[1]);
    const suffix = match[2];
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion || target === 0) {
        element.textContent = `${target}${suffix}`;
        return;
    }

    const startedAt = performance.now();
    const duration = 900;

    function update(now) {
        const progress = Math.min((now - startedAt) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        element.textContent = `${Math.round(target * eased)}${suffix}`;
        if (progress < 1) requestAnimationFrame(update);
    }

    requestAnimationFrame(update);
}

if ('IntersectionObserver' in window && statNumbers.length) {
    const statsObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                statNumbers.forEach(animateNumber);
                observer.disconnect();
            }
        });
    }, { threshold: 0.35 });

    const statsBar = document.querySelector('.stats-bar');
    if (statsBar) statsObserver.observe(statsBar);
}

// Add subtle shadow and backdrop behavior to the sticky navbar while scrolling.
const navbar = document.querySelector('.modern-navbar');
if (navbar) {
    const updateNavbar = () => navbar.classList.toggle('scrolled', window.scrollY > 12);
    updateNavbar();
    window.addEventListener('scroll', updateNavbar, { passive: true });
}

// Automatically cycle the hero photos while allowing manual selection.
const heroSlider = document.querySelector('[data-hero-slider]');
if (heroSlider) {
    const slides = Array.from(heroSlider.querySelectorAll('.hero-slide'));
    const dots = Array.from(heroSlider.querySelectorAll('.hero-slider-dot'));
    const previousButton = heroSlider.querySelector('.hero-slider-prev');
    const nextButton = heroSlider.querySelector('.hero-slider-next');
    let slideIndex = 0;

    function updateSlider() {
        slides.forEach((slide, index) => {
            const isSelected = index === slideIndex;
            slide.classList.toggle('active', isSelected);
            slide.setAttribute('aria-hidden', String(!isSelected));
        });
        dots.forEach((dot, index) => {
            const isSelected = index === slideIndex;
            dot.classList.toggle('active', isSelected);
            dot.setAttribute('aria-pressed', String(isSelected));
        });
    }

    function moveTo(index) {
        slideIndex = (index + slides.length) % slides.length;
        updateSlider();
    }

    previousButton.addEventListener('click', () => moveTo(slideIndex - 1));
    nextButton.addEventListener('click', () => moveTo(slideIndex + 1));
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => moveTo(index));
    });

    window.setInterval(() => {
        if (!document.hidden) moveTo(slideIndex + 1);
    }, 2000);

    updateSlider();
    heroSlider.classList.add('is-ready');
}
