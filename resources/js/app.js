const toggle = document.querySelector('.mobile-toggle');
const menu = document.querySelector('.nav-secondary');

toggle?.addEventListener('click', () => {
    const open = menu.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(open));
});
