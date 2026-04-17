</main>

<footer class="site-footer">
    <span class="footer-watermark" aria-hidden="true">TJSGYM</span>
    <div class="footer-inner">
        <div class="footer-section">
            <h4><?php _e("TJ's Gymnastics Club", 'tjs-gymnastics'); ?></h4>
            <p><?php _e('Building confidence through gymnastics since 1988. Serving families across Wimbledon, Raynes Park and South West London.', 'tjs-gymnastics'); ?></p>
        </div>
        <div class="footer-section">
            <h4><?php _e('Our Partners', 'tjs-gymnastics'); ?></h4>
            <ul class="footer-links">
                <li><a href="https://www.british-gymnastics.org" target="_blank" rel="noopener"><?php _e('British Gymnastics', 'tjs-gymnastics'); ?></a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4><?php _e('Find Us', 'tjs-gymnastics'); ?></h4>
            <div class="footer-find-us">
                <address>
                    <?php _e('Raynes Park Sports Pavilion<br>Taunton Avenue<br>London SW20 0BH', 'tjs-gymnastics'); ?>
                </address>
                <div class="footer-find-us-contact">
                    <a href="tel:01252702295">01252 702295</a>
                    <a href="tel:07885103080">07885 103080</a>
                    <a href="mailto:info@tjsgymclub.co.uk">info@tjsgymclub.co.uk</a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> <?php _e("TJ's Gymnastics Club, Wimbledon. All rights reserved.", 'tjs-gymnastics'); ?></p>
        <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php _e('Privacy Policy', 'tjs-gymnastics'); ?></a>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
