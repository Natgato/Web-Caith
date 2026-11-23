(() => {
    const menuButton = document.querySelector('.menu-toggle');
    const nav = document.querySelector('#main-nav');
    const mobile = window.matchMedia('(max-width: 960px)');
    const setMenu = (open) => {
        nav.hidden = mobile.matches && !open;
        menuButton.setAttribute('aria-expanded', String(open));
        menuButton.querySelector('[data-menu-label]').textContent = open ? 'Cerrar' : 'Menú';
    };
    const syncMenu = () => { menuButton.hidden = !mobile.matches; setMenu(false); };
    syncMenu();
    mobile.addEventListener('change', syncMenu);
    menuButton.addEventListener('click', () => setMenu(menuButton.getAttribute('aria-expanded') !== 'true'));
    nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setMenu(false)));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && mobile.matches && menuButton.getAttribute('aria-expanded') === 'true') { setMenu(false); menuButton.focus(); }
    });

    const homeHeader = document.querySelector('[data-home-header]');
    const homeHero = document.querySelector('[data-home-hero]');
    if (homeHeader && homeHero) {
        homeHeader.classList.add('is-sticky');
        let frame = 0;
        const updateHeader = () => {
            frame = 0;
            if (menuButton.getAttribute('aria-expanded') === 'true') return;
            const heroHasPassed = homeHero.getBoundingClientRect().bottom <= homeHeader.getBoundingClientRect().bottom;
            homeHeader.classList.toggle('is-wine', heroHasPassed);
        };
        const scheduleUpdate = () => {
            if (!frame) frame = window.requestAnimationFrame(updateHeader);
        };
        const sizeObserver = new ResizeObserver(scheduleUpdate);
        sizeObserver.observe(homeHeader);
        sizeObserver.observe(homeHero);
        window.addEventListener('scroll', scheduleUpdate, { passive: true });
        window.addEventListener('pageshow', scheduleUpdate);
        updateHeader();
    }

    const controls = document.querySelector('.history-controls');
    const years = [...document.querySelectorAll('[data-year]')];
    const stories = [...document.querySelectorAll('[data-history]')];
    const selectYear = (year) => {
        years.forEach((button) => button.setAttribute('aria-pressed', String(button.dataset.year === year)));
        stories.forEach((story) => { const selected = story.dataset.history === year; story.hidden = !selected; story.classList.toggle('is-selected', selected); });
    };
    if (!controls || !years.length) return;
    controls.hidden = false;
    const fromHash = stories.find((story) => '#'+story.id === window.location.hash);
    selectYear(fromHash ? fromHash.dataset.history : years[0].dataset.year);
    years.forEach((button, index) => {
        button.addEventListener('click', () => selectYear(button.dataset.year));
        button.addEventListener('keydown', (event) => {
            if (!['ArrowRight', 'ArrowLeft', 'Home', 'End'].includes(event.key)) return;
            event.preventDefault();
            const next = event.key === 'Home' ? 0 : event.key === 'End' ? years.length - 1 : (index + (event.key === 'ArrowRight' ? 1 : -1) + years.length) % years.length;
            years[next].focus(); selectYear(years[next].dataset.year);
        });
    });
})();
