/**
 * Open share links in a new window
 *
 * @todo Determine share link off a data attribute not a class
 * @todo Enable height and width to be set by data attribute
 */
const initialize = () => {
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('js-share')) {
            const url = e.target.getAttribute('href');

            if (url && url.indexOf('http') === 0) {
                const newWindow = window.open(url, '', 'height=450, width=700');

                if (window.focus) {
                    newWindow.focus();
                }

                e.preventDefault();
            }
        }
    });
};

document.addEventListener('DOMContentLoaded', initialize);
