(function () {
    const sidebar = document.querySelector('.navbar-vertical');
    const toggle = document.getElementById('sidebar-toggle');
    if (!sidebar || !toggle) return;
    const desktop = window.matchMedia('(min-width: 992px)');
    const popup = document.createElement('div');
    popup.className = 'sidebar-popup';
    popup.id = 'sidebar-submenu';
    popup.hidden = true;
    document.body.append(popup);
    let activeLink = null;
    let compact = false;
    try { compact = localStorage.getItem('sidebar-compact') === 'true'; } catch (error) {}

    function closePopup(restoreFocus = false) {
        popup.hidden = true;
        if (activeLink) {
            activeLink.setAttribute('aria-expanded', 'false');
            if (restoreFocus) activeLink.focus();
        }
        activeLink = null;
    }

    function applyMode() {
        closePopup();
        document.body.classList.toggle('sidebar-compact', compact);
        toggle.setAttribute('aria-expanded', String(!compact));
        const label = compact ? 'Perbesar sidebar' : 'Kecilkan sidebar';
        toggle.title = label;
        toggle.setAttribute('aria-label', label);
        sidebar.querySelectorAll('.nav-link').forEach(link => {
            const name = link.querySelector('.nav-link-title')?.textContent.trim();
            if (name) {
                link.setAttribute('aria-label', name);
                link.title = compact && desktop.matches ? name : '';
            }
        });
        window.dispatchEvent(new Event('resize'));
    }

    toggle.addEventListener('click', () => {
        sidebar.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(link => {
            bootstrap.Dropdown.getInstance(link)?.hide();
        });
        compact = !compact;
        try { localStorage.setItem('sidebar-compact', String(compact)); } catch (error) {}
        applyMode();
    });

    sidebar.addEventListener('click', event => {
        const link = event.target.closest('[data-bs-toggle="dropdown"]');
        if (!link || !compact || !desktop.matches) return;
        event.preventDefault();
        event.stopPropagation();
        const wasOpen = activeLink === link;
        closePopup();
        if (wasOpen) return;
        const menu = link.parentElement.querySelector('.dropdown-menu');
        if (!menu) return;
        const heading = document.createElement('div');
        heading.className = 'sidebar-popup-heading';
        heading.textContent = link.querySelector('.nav-link-title').textContent.trim();
        popup.replaceChildren(heading, ...Array.from(menu.children, child => child.cloneNode(true)));
        popup.style.left = `${sidebar.getBoundingClientRect().right + 8}px`;
        popup.style.maxHeight = `${window.innerHeight - 24}px`;
        popup.hidden = false;
        const top = Math.max(12, Math.min(link.getBoundingClientRect().top, window.innerHeight - popup.offsetHeight - 12));
        popup.style.top = `${top}px`;
        activeLink = link;
        link.setAttribute('aria-expanded', 'true');
        popup.querySelector('a, button')?.focus();
    });

    document.addEventListener('click', event => {
        if (!popup.contains(event.target)) closePopup();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && activeLink) closePopup(true);
    });
    window.addEventListener('resize', () => closePopup());
    document.addEventListener('scroll', () => closePopup(), true);
    desktop.addEventListener('change', applyMode);
    applyMode();
})();
