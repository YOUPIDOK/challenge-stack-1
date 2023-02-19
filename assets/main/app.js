// Font Awesome
import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
FontAwesome.config.mutateApproach = 'sync';

// Css
import './styles/app.scss';

// Libs
import 'bootstrap/dist/js/bootstrap.min';

// Start stimulus
import './stimulus';

if (navigator && navigator.serviceWorker) {
    navigator.serviceWorker.register('sw.js');
}