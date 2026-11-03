const menuButton = document.getElementById('menuButton');
const navMenu = document.getElementById('navMenu');

menuButton.addEventListener('click', () => {
    const isOpen = navMenu.classList.toggle('open');
    menuButton.classList.toggle('active', isOpen);
    menuButton.setAttribute('aria-expanded', String(isOpen));
});

const options = document.getElementById('options');

if (options) {
    options.addEventListener('change', function() {
        const selectedOption = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('filter', selectedOption);
        window.location.href = url.toString();
    });
}

document.addEventListener('click', (event) => {
    if (!navMenu.contains(event.target) && !menuButton.contains(event.target)) {
        navMenu.classList.remove('open');
        menuButton.classList.remove('active');
        menuButton.setAttribute('aria-expanded', 'false');
    }
});
