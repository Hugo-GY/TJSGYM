<?php
/**
 * Template Name: Child Info Sheet Page
 */
get_header();

$eyebrow = get_field('eyebrow') ?: 'Forms & documents';
$title = get_field('title') ?: 'Child Information <em>Sheet</em>';
$content = get_field('content');
$download_link = get_field('download_link') ?: content_url('/uploads/docs/v8-Information-Sheet-TJS.docx');
?>

<section class="child-sheet-section" aria-labelledby="child-sheet-card-title">
    <div class="container">
        <div class="child-sheet-content">
            <p class="child-sheet-eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <h1 id="child-sheet-card-title"><?php echo wp_kses_post($title); ?></h1>
            <?php if ($content): ?>
                <p class="child-sheet-copy"><?php echo wp_kses_post($content); ?></p>
            <?php else: ?>
                <p class="child-sheet-copy"><?php _e('Download the form and email the completed copy to', 'tjs-gymnastics'); ?> <a href="mailto:info@tjsgymclub.co.uk">info@tjsgymclub.co.uk</a>.</p>
            <?php endif; ?>
            <a class="child-sheet-download-link" href="<?php echo esc_url($download_link); ?>" target="_blank" rel="noopener">
                <span class="child-sheet-download-icon" aria-hidden="true">↓</span>
                <span><?php _e('Download Form', 'tjs-gymnastics'); ?></span>
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
