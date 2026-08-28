document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('appSidebar');
    const menuButton = document.getElementById('menuButton');
    const overlay = document.getElementById('sidebarOverlay');

    const setSidebarOpen = (open) => {
        sidebar?.classList.toggle('is-open', open);
        overlay?.classList.toggle('is-open', open);
        menuButton?.setAttribute('aria-expanded', String(open));
    };

    menuButton?.addEventListener('click', () => {
        setSidebarOpen(!sidebar?.classList.contains('is-open'));
    });
    overlay?.addEventListener('click', () => setSidebarOpen(false));

    const haeSearch = document.getElementById('pesquisaHae');
    haeSearch?.addEventListener('input', () => {
        const term = haeSearch.value.toLocaleLowerCase('pt-BR').trim();

        document.querySelectorAll('[data-hae-item]').forEach((item) => {
            item.hidden = term !== ''
                && !item.textContent.toLocaleLowerCase('pt-BR').includes(term);
        });
    });

    const userSearch = document.getElementById('buscarUsuario');
    userSearch?.addEventListener('input', () => {
        const term = userSearch.value.toLocaleLowerCase('pt-BR').trim();

        document.querySelectorAll('[data-user-chip]').forEach((item) => {
            item.hidden = term !== ''
                && !item.textContent.toLocaleLowerCase('pt-BR').includes(term);
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
});
