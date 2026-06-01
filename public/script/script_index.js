let nav = document.querySelector('#nav-overlay');

window.addEventListener('scroll', function(){
    if (window.scrollY >= 10) {
        nav.style.borderRadius = '4rem';
        nav.style.top = '0';
        nav.style.left = '50%';
        nav.style.transform = 'translateX(-50%)';
        nav.style.width = '80%';
        nav.style.zIndex = '1050';
        nav.style.position = 'fixed';
        nav.style.marginTop = '1rem';
        nav.classList.add('nav-glass')
    } else {
        nav.style.borderRadius = '';
        nav.style.position = '';
        nav.style.top = '';
        nav.style.left = '';
        nav.style.transform = '';
        nav.style.width = '';
        nav.style.zIndex = '';
        nav.style.marginTop = '';
        nav.style.backdropFilter = '';
        nav.style.webkitBackdropFilter = '';
        nav.style.backgroundColor = '';
        nav.classList.remove('nav-glass')
    }
});