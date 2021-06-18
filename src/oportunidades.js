require('./_polyfill_element-closest');
import Flipping from 'flipping/lib/adapters/web';

const flip = new Flipping();

document.addEventListener('DOMContentLoaded', function() {
    let tabs = document.querySelectorAll('button[data-bs-toggle="pill"]')
    tabs.forEach(function(tab) {
        tab.addEventListener('show.bs.tab', function() {
            document.querySelector(this.dataset.bsTarget).querySelectorAll('.oportunidade--open').forEach(function(open) {
                open.classList.remove('oportunidade--open');
                open.removeAttribute('style');
            });
        });
    });

    const classes = ['animate__animated', 'animate__fadeIn', 'animate__fast'];

    document.querySelectorAll('.oportunidade__btn-toggle').forEach(function(btn) {
        const oportunidades = btn.closest('.oportunidades');
        const oportunidade = btn.closest('.oportunidade');

        oportunidade.addEventListener('animationend', function(e) {
            e.stopPropagation();
            this.classList.remove(...classes);
        });

        const colCount = window.getComputedStyle(oportunidades).getPropertyValue('grid-template-columns').split(' ').length;
        const index = [...oportunidades.children].indexOf(oportunidade);
        const rowPosition = Math.floor(index / colCount);
        const colPosition = index % colCount;

        btn.addEventListener('click', function(e) {
            e.preventDefault();

            flip.read();

            if (oportunidade.classList.contains('oportunidade--open')) { // Close
                oportunidade.classList.remove('oportunidade--open');
                oportunidade.removeAttribute('style');
                oportunidade.classList.add(...classes);
            } else { // Open
                oportunidades.querySelectorAll('.oportunidade--open').forEach(function(open) { // Close others
                    open.classList.remove('oportunidade--open');
                    open.removeAttribute('style');
                    open.classList.add(...classes);
                });

                if (colPosition < colCount - 1) {
                    oportunidade.style.gridColumn = (colPosition + 1) + ' / span 2';
                } else if (colPosition == colCount - 1) {
                    oportunidade.style.gridColumn = (colCount - 1) + ' / span 2';
                }

                oportunidade.style.gridRow = (rowPosition + 1) + ' / span 3';
                oportunidade.classList.add('oportunidade--open');
                oportunidade.classList.add(...classes);
            }

            flip.flip();
        });
    });
});
