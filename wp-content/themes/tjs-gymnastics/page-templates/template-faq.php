<?php
/**
 * Template Name: FAQ Page
 */
get_header();

$hero_kicker = get_field('hero_kicker') ?: 'Helpful information for parents';
$hero_title = get_field('hero_title') ?: 'Frequently Asked <em>Questions</em>';
$hero_subtitle = get_field('hero_subtitle') ?: 'Answers to the practical questions parents usually ask about joining, trial classes and changes during the term.';

// Query FAQ posts
$faq_query = new WP_Query(array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'category_name' => 'faq',
    'orderby' => 'date',
    'order' => 'ASC',
));
?>

<section class="faq-hero" aria-label="<?php _e('Frequently asked questions', 'tjs-gymnastics'); ?>">
    <div class="container faq-hero-inner">
        <p class="faq-kicker"><?php echo esc_html($hero_kicker); ?></p>
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
        <p class="faq-hero-sub"><?php echo esc_html($hero_subtitle); ?></p>
    </div>
</section>

<section class="faq-section" aria-label="<?php _e('Frequently asked questions list', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="faq-shell">
            <div class="tc-accordion faq-accordion" role="list">
                <?php
                if ($faq_query->have_posts()):
                    $faq_index = 1;
                    while ($faq_query->have_posts()): $faq_query->the_post();
                        $question = get_the_title();
                        $answer = get_the_content();
                ?>
                    <div class="tc-item faq-item" role="listitem">
                        <button class="tc-trigger faq-trigger" aria-expanded="false" aria-controls="faq-<?php echo $faq_index; ?>">
                            <span><?php echo esc_html($question); ?></span>
                            <span class="tc-icon" aria-hidden="true"></span>
                        </button>
                        <div class="tc-panel faq-panel" id="faq-<?php echo $faq_index; ?>" hidden>
                            <?php echo wp_kses_post($answer); ?>
                        </div>
                    </div>
                <?php
                        $faq_index++;
                    endwhile;
                    wp_reset_postdata();
                else:
                    // Default FAQ items
                    $default_faqs = array(
                        array(
                            'q' => 'How do term bookings work?',
                            'a' => 'Most of our classes run on a termly booking basis. We usually encourage children to join for the full term because the coaching, equipment set-ups and challenges build progressively week by week. If spaces are available, we can sometimes accommodate a mid-term start, but that is handled on a case-by-case basis.'
                        ),
                        array(
                            'q' => 'Can my child have a trial class?',
                            'a' => 'Yes, trial classes are usually available from the third week of term onwards rather than in the first two weeks. A first class can feel like a lot to take in, so some children need a little time to settle. If your child would be joining after the term has already started, we may suggest a discounted partial booking instead of a one-off trial so they have a fair chance to get comfortable.'
                        ),
                        array(
                            'q' => 'Can we book for only half a term?',
                            'a' => 'Half-term bookings are only offered in exceptional situations. If that is what you need, please contact Gill directly and explain the circumstances. We will always try to find the most sensible option for your family where possible.'
                        ),
                        array(
                            'q' => 'What happens if we miss sessions or need to stop?',
                            'a' => 'We do not offer make-up classes or refunds for missed sessions. That said, if after a few weeks you feel the class is not the right fit for your child, we are happy to refund the remaining sessions because we want to help families find the right experience. In serious medical circumstances, we can also make exceptions to the usual refund policy.'
                        ),
                    );
                    $faq_index = 1;
                    foreach ($default_faqs as $faq):
                ?>
                    <div class="tc-item faq-item" role="listitem">
                        <button class="tc-trigger faq-trigger" aria-expanded="false" aria-controls="faq-<?php echo $faq_index; ?>">
                            <span><?php echo esc_html($faq['q']); ?></span>
                            <span class="tc-icon" aria-hidden="true"></span>
                        </button>
                        <div class="tc-panel faq-panel" id="faq-<?php echo $faq_index; ?>" hidden>
                            <p><?php echo esc_html($faq['a']); ?></p>
                        </div>
                    </div>
                <?php
                        $faq_index++;
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
