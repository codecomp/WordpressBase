import 'core-js/fn/symbol/for';
import 'whatwg-fetch';
import ValidForm from '@pageclip/valid-form';

/**
 * Setup forms using js-process-form to fire a ajax request to WordPress for processing
 *
 * @todo Test full functionality after refactoring
 */
const initialize = () => {
    for (const form of document.getElementsByClassName('js-process-form')) {
        ValidForm(form, { errorPlacement: 'after' });

        form.addEventListener('submit', handleSubmit);
    }
};

/**
 * Event handler for submitting forms
 *
 * @param {Event} e
 */
function handleSubmit(e) {
    e.preventDefault();
    const form = e.target;

    // Stop repeated submissions
    if (form.classList.contains('is-loading')) {
        return false;
    }

    form.classList.add('is-loading');

    // Format request data
    const data = new FormData(form);
    data.append('action', form.getAttribute('action'));
    data.append('security', WP.nonce);

    // Send the request
    let responseStatus, message;

    fetch(WP.ajax, {
        method: 'POST',
        body: data,
    })
        .then((response) => {
            responseStatus = response.status;
            return response.json();
        })
        .then((response) => {
            switch (responseStatus) {
                case 200:
                case 201:
                case 202:
                    if (response.data.message) {
                        message = response.data.message;
                    } else {
                        message = WP.translate.thanks;
                    }
                    break;
                default:
                    message = WP.translate.error;
                    break;
            }

            handleResponse(form, message);
        })
        .catch(() => {
            message = WP.translate.thanks;
            handleResponse(form, message);
        });
}

/**
 * Process response from AJAX requests
 *
 * @param {Element} form
 * @param {String} message
 */
function handleResponse(form, message) {
    const thanks = document.createElement('div');
    thanks.classList.add('response');
    thanks.innerHTML = `<p class="response__thanks">${message}</p>`;

    form.parentNode.replaceChild(thanks, form);
}

document.addEventListener('DOMContentLoaded', initialize);
