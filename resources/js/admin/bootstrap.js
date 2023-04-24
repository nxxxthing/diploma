window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    /** default requires */
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    /** requires for adminlte */
    require('overlayscrollbars');
    require('admin-lte');

    require('bootstrap');

    require('select2');

    window.toastr = require('toastr');

    /** other utils and packages */
    require('bootstrap-toggle');
    require('bootstrap-datetimepicker/src/js/bootstrap-datetimepicker');

    require('jquery-validation');

    require('datatables.net-bs4');
    window.Resumable = require('resumablejs')
} catch (e) {
    console.group('admin/bootstrap')
    console.error(e)
    console.groupEnd()
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
