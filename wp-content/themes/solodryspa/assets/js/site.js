(() => {
    const drawer = document.getElementById('solo-menu-drawer');
    const nav = document.getElementById('solo-primary-nav');
    const toggle = document.querySelector('.solo-menu-toggle[aria-controls="solo-menu-drawer"]');

    if (!drawer || !nav || !toggle || typeof drawer.showModal !== 'function') {
        return;
    }

    let closing = false;

    async function closeDrawer() {
        if (!drawer.open || closing) {
            return;
        }

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
            typeof drawer.animate !== 'function') {
            drawer.close();
            return;
        }

        closing = true;
        drawer.classList.add('is-closing');
        const animation = drawer.animate([
            { transform: window.getComputedStyle(drawer).transform },
            { transform: 'translateX(100%)' },
        ], { duration: 250, easing: 'ease-in', fill: 'forwards' });
        await animation.finished.catch(() => {});
        drawer.close();
        animation.cancel();
        drawer.classList.remove('is-closing');
        closing = false;
    }

    drawer.append(nav);
    toggle.addEventListener('click', () => {
        drawer.showModal();
        toggle.setAttribute('aria-expanded', 'true');
    });
    drawer.addEventListener('close', () => {
        toggle.setAttribute('aria-expanded', 'false');
    });
    drawer.addEventListener('cancel', (event) => {
        event.preventDefault();
        closeDrawer();
    });
    drawer.querySelector('form[method="dialog"]').addEventListener('submit', (event) => {
        event.preventDefault();
        closeDrawer();
    });
    drawer.addEventListener('click', (event) => {
        if (event.target !== drawer) {
            return;
        }

        const bounds = drawer.getBoundingClientRect();
        if (event.clientX < bounds.left || event.clientX > bounds.right ||
            event.clientY < bounds.top || event.clientY > bounds.bottom) {
            closeDrawer();
        }
    });
    nav.addEventListener('click', (event) => {
        if (event.target.closest('a[href]') && !event.defaultPrevented &&
            !event.ctrlKey && !event.metaKey && !event.shiftKey && !event.altKey) {
            closeDrawer();
        }
    });
    toggle.hidden = false;
})();