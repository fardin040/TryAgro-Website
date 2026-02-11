(function () {
    var body = document.body;
    var menuToggle = document.querySelector('.menu-toggle');
    var siteNav = document.querySelector('.site-nav');

    function closeMenu() {
        if (!menuToggle || !siteNav) {
            return;
        }

        siteNav.classList.remove('is-open');
        menuToggle.setAttribute('aria-expanded', 'false');
        body.classList.remove('menu-open');
    }

    if (menuToggle && siteNav) {
        menuToggle.addEventListener('click', function () {
            var isOpen = siteNav.classList.toggle('is-open');
            menuToggle.setAttribute('aria-expanded', String(isOpen));
            body.classList.toggle('menu-open', isOpen);
        });

        siteNav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.matchMedia('(max-width: 47.99rem)').matches) {
                    closeMenu();
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMenu();
            }
        });

        document.addEventListener('click', function (event) {
            if (!siteNav.contains(event.target) && !menuToggle.contains(event.target)) {
                closeMenu();
            }
        });
    }

    var slides = Array.prototype.slice.call(document.querySelectorAll('[data-slide]'));
    var sliderDots = document.querySelector('.slider-dots');
    var current = 0;
    var rotationTimer = null;

    function showSlide(index) {
        slides.forEach(function (slide, i) {
            slide.classList.toggle('is-active', i === index);
            slide.setAttribute('aria-hidden', i === index ? 'false' : 'true');
        });

        if (sliderDots) {
            sliderDots.querySelectorAll('.slider-dot').forEach(function (dot, i) {
                dot.setAttribute('aria-selected', i === index ? 'true' : 'false');
            });
        }

        current = index;
    }

    function nextSlide() {
        showSlide((current + 1) % slides.length);
    }

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function startRotation() {
        if (slides.length < 2 || reduceMotion) {
            return;
        }

        if (rotationTimer) {
            window.clearInterval(rotationTimer);
        }

        rotationTimer = window.setInterval(nextSlide, 5000);
    }

    if (slides.length) {
        if (sliderDots) {
            slides.forEach(function (_, index) {
                var dot = document.createElement('button');
                dot.className = 'slider-dot';
                dot.type = 'button';
                dot.setAttribute('role', 'tab');
                dot.setAttribute('aria-selected', 'false');
                dot.setAttribute('aria-label', 'Slide ' + (index + 1));
                dot.addEventListener('click', function () {
                    showSlide(index);
                    startRotation();
                });
                sliderDots.appendChild(dot);
            });
        }

        showSlide(0);
        startRotation();
    }

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (event) {
            var targetId = anchor.getAttribute('href').slice(1);
            var target = targetId ? document.getElementById(targetId) : null;
            if (target) {
                event.preventDefault();
                target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
            }
        });
    });
})();
