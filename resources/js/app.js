import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Import feed interactions if on feed page
if (document.querySelector('.like-form') || document.querySelector('.comment-form')) {
    import('./feed.js');
}
