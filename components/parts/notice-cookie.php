<?php if (!isset($_COOKIE['cookie-notice'])): ?>
    <div class="cookie-notice">
        <h2><?php _e('This website uses cookies', 'tmp') ?></h2>
        <p><?php _e('This website uses cookies to improve user experience. By using our website you consent to all cookies in accordance with our Cookie Policy.', 'tmp') ?></p>
        <a href="#" class="agree button"><?php _e('I agree', 'tmp') ?></a>
        <a href="/cookies/" title="<?php _e('Cookie policy', 'tmp') ?>" class="more button"><?php _e('Read more', 'tmp') ?></a>
    </div>
<?php endif; ?>