<?php if (!isset($_COOKIE['cookie-notice'])): ?>
    <div class="cookie-notice">
        <p><?php _e('This website uses cookies to improve user experience. By using our website you consent to the storage of cookies from this website.', 'tmp') ?></p>
        <a href="#" class="agree button"><?php _e('I agree', 'tmp') ?></a>
    </div>
<?php endif; ?>