import bowser from 'bowser';

/**
 * Add classes to body for loaded and the browser being used
 */
const initialize = () => {
    document.body.classList.add('is-loaded');
    if(typeof bowser !== 'undefined'){
        if(bowser.name){
            document.body.classList.add(bowser.name.replace(/\s+/g, '-').toLowerCase());
        }
        if(bowser.name && bowser.version){
            document.body.classList.add(bowser.name.replace(/\s+/g, '-').toLowerCase() + '-' + bowser.version.replace(/\s+/g, '-').toLowerCase()); // eslint-disable-line max-len
        }
    }
}

document.addEventListener('DOMContentLoaded', initialize);