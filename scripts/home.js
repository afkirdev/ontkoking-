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
const muziek = document.getElementById('achtergrondmuziek');
const muziekKnop = document.getElementById('muziekKnop');

if (muziek && muziekKnop) {
    const voorkeurSleutel = 'ontkoking-muziek';
    let gepauzeerd = false;
    try {
        gepauzeerd = sessionStorage.getItem(voorkeurSleutel) === 'uit';
    } catch {
        // Muziek blijft bruikbaar als browseropslag niet beschikbaar is.
    }
    muziek.volume = 0.2;
    muziekKnop.hidden = false;

    function toonMuziekStatus() {
        muziekKnop.textContent = muziek.paused ? 'Muziek afspelen' : 'Muziek pauzeren';
        muziekKnop.setAttribute('aria-pressed', String(!muziek.paused));
    }

    function speelMuziek() {
        if (!gepauzeerd) muziek.play().catch(toonMuziekStatus);
    }

    muziek.addEventListener('play', toonMuziekStatus);
    muziek.addEventListener('pause', toonMuziekStatus);
    muziekKnop.addEventListener('click', () => {
        gepauzeerd = !muziek.paused;
        try {
            sessionStorage.setItem(voorkeurSleutel, gepauzeerd ? 'uit' : 'aan');
        } catch {
            // De knop werkt ook zonder browseropslag.
        }
        if (gepauzeerd) muziek.pause();
        else speelMuziek();
    });

    // Als autoplay geblokkeerd is, probeer opnieuw bij de eerste gebruikersactie.
    document.addEventListener('click', (event) => {
        if (!muziekKnop.contains(event.target) && muziek.paused) speelMuziek();
    });
    document.addEventListener('keydown', (event) => {
        if (!muziekKnop.contains(event.target) && muziek.paused) speelMuziek();
    });
    if (gepauzeerd) {
        muziek.autoplay = false;
        muziek.pause();
    } else {
        speelMuziek();
    }
}
}

document.addEventListener('click', (event) => {
    if (!navMenu.contains(event.target) && !menuButton.contains(event.target)) {
        navMenu.classList.remove('open');
        menuButton.classList.remove('active');
        menuButton.setAttribute('aria-expanded', 'false');
    }
});
