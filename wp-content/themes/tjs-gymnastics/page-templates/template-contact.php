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

$contact_form_id = 0;

if (shortcode_exists('fluentform')) {
    $configured_form_id = function_exists('get_field') ? (int) get_field('contact_form_id') : 0;

    if ($configured_form_id > 0) {
        $contact_form_id = $configured_form_id;
    } else {
        global $wpdb;

        $candidate_titles = array(
            "TJ's Contact Us",
            'Contact Us',
            'Contact Form Demo',
        );

        foreach ($candidate_titles as $candidate_title) {
            $found_form_id = (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}fluentform_forms WHERE title = %s AND status = %s ORDER BY id ASC LIMIT 1",
                    $candidate_title,
                    'published'
                )
            );

            if ($found_form_id > 0) {
                $contact_form_id = $found_form_id;
                break;
            }
        }
    }
}
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
            <?php if ($contact_form_id > 0): ?>
                <div class="contact-form contact-form--fluent">
                    <?php echo do_shortcode(sprintf('[fluentform id="%d"]', $contact_form_id)); ?>
                </div>
            <?php else: ?>
                <div class="contact-form-unavailable" role="status">
                    <p><?php _e('Our contact form is temporarily unavailable. Please use the phone numbers above and we will help you directly.', 'tjs-gymnastics'); ?></p>
                    <?php if (current_user_can('manage_options')): ?>
                        <p class="contact-form-note"><?php _e('Admin note: no published Fluent Form was found for this page.', 'tjs-gymnastics'); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
