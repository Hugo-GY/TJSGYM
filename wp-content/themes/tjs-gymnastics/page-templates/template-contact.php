<?php
/**
 * Template Name: Contact Page
 */
get_header();

$hero_eyebrow = get_field('hero_eyebrow') ?: 'Get in touch';
$hero_title = get_field('hero_title') ?: 'Contact <em>Us</em>';
$hero_subtitle = get_field('hero_subtitle') ?: 'If you\'re choosing the right class, arranging a trial, or just have a general question, we\'re here to help you find the best next step for your child.';
$intro_text = get_field('intro_text') ?: 'Tell us a little about your child and what you\'d like help with.';
$callout_text = get_field('callout_text') ?: 'Please call <a href="tel:01252702295">01252 702295</a> or <a href="tel:07885103080">07885 103080</a>.';
?>

<!-- ── Page Hero ──────────────────────────────────── -->
<section class="page-hero" aria-label="<?php _e('Contact TJ\'s Gymnastics Club', 'tjs-gymnastics'); ?>">
    <div class="container">
        <p class="page-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
        <p class="page-hero-sub"><?php echo esc_html($hero_subtitle); ?></p>
    </div>
</section>

<!-- ── Contact Form ───────────────────────────────── -->
<section class="contact-form-section" aria-label="<?php _e('Contact form', 'tjs-gymnastics'); ?>">
    <div class="container">
        <p class="contact-intro"><?php echo esc_html($intro_text); ?></p>
        <p class="contact-callout"><?php echo wp_kses_post($callout_text); ?></p>
        <div class="contact-card">
            <?php
            // Check if Contact Form 7 is active
            if (function_exists('wpcf7_enqueue_scripts')) {
                echo do_shortcode('[contact-form-7 id="contact" title="Contact Form"]');
            } else {
                // Fallback form
            ?>
                <form class="contact-form" method="post" action="">
                    <div>
                        <label for="parent-name"><?php _e('Parent / Carer Name', 'tjs-gymnastics'); ?></label>
                        <input type="text" id="parent-name" name="parent-name" autocomplete="name">
                    </div>

                    <div>
                        <label for="email"><?php _e('Email Address', 'tjs-gymnastics'); ?></label>
                        <input type="email" id="email" name="email" autocomplete="email">
                    </div>

                    <div>
                        <label for="phone"><?php _e('Phone Number', 'tjs-gymnastics'); ?></label>
                        <input type="tel" id="phone" name="phone" autocomplete="tel">
                    </div>

                    <div>
                        <label for="child-dob"><?php _e('Child\'s date of birth', 'tjs-gymnastics'); ?></label>
                        <input type="date" id="child-dob" name="child-dob" autocomplete="bday">
                    </div>

                    <div>
                        <label for="class-interest"><?php _e('Class of Interest', 'tjs-gymnastics'); ?></label>
                        <select id="class-interest" name="class-interest">
                            <option value="" selected disabled><?php _e('Please select', 'tjs-gymnastics'); ?></option>
                            <option value="tiddler-gym"><?php _e('Tiddler Gym', 'tjs-gymnastics'); ?></option>
                            <option value="toddler-gym"><?php _e('Toddler Gym', 'tjs-gymnastics'); ?></option>
                            <option value="mini-gym"><?php _e('Mini Gym', 'tjs-gymnastics'); ?></option>
                            <option value="gymnastics"><?php _e('Gymnastics', 'tjs-gymnastics'); ?></option>
                            <option value="not-sure-yet"><?php _e('Not sure yet', 'tjs-gymnastics'); ?></option>
                        </select>
                    </div>

                    <div>
                        <label for="message"><?php _e('Message', 'tjs-gymnastics'); ?></label>
                        <textarea id="message" name="message" rows="6"></textarea>
                    </div>

                    <div class="contact-submit-row">
                        <button type="button" class="btn btn-magenta"><?php _e('Send Enquiry', 'tjs-gymnastics'); ?></button>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
