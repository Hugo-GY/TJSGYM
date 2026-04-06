<?php
/**
 * Template Name: Classes Page
 */
get_header();

$hero_eyebrow = get_field('hero_eyebrow') ?: 'Our Programmes';
$hero_title = get_field('hero_title') ?: 'Classes for <em>Every</em> Stage';
$hero_subtitle = get_field('hero_subtitle') ?: 'From Babies and Crawling Toddlers to Confident Walkers — we have a structured, fun programme designed for every stage of your child\'s development.';
?>

<!-- ── Page Hero ──────────────────────────────────── -->
<section class="page-hero" aria-label="<?php _e('Classes overview', 'tjs-gymnastics'); ?>">
    <div class="container">
        <p class="page-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
        <p class="page-hero-sub"><?php echo esc_html($hero_subtitle); ?></p>
    </div>
</section>

<!-- ── Class Cards ─────────────────────────────────── -->
<section class="class-cards-section" aria-label="<?php _e('All classes', 'tjs-gymnastics'); ?>">
    <div class="container">
        <div class="class-cards-grid">
            <?php
            if (have_rows('class_cards')):
                while (have_rows('class_cards')): the_row();
                    $class_name = get_sub_field('class_name');
                    $age_range = get_sub_field('age_range');
                    $description = get_sub_field('description');
                    $price = get_sub_field('price');
                    $price_label = get_sub_field('price_label');
                    $price_note = get_sub_field('price_note');
                    $button_text = get_sub_field('button_text') ?: 'Details & Book';
                    $button_link = get_sub_field('button_link');
                    $image = get_sub_field('image');
                    $class_modifier = get_sub_field('class_modifier'); // tiddler, toddler, minigym, gym
            ?>
                <article class="class-card class-card--<?php echo esc_attr($class_modifier); ?>">
                    <div class="class-card-img-wrap" aria-hidden="true">
                        <?php if ($image): ?>
                            <img src="<?php echo esc_url($image['url']); ?>" alt="" class="class-card-img" loading="lazy">
                        <?php endif; ?>
                        <span class="age-pill-overlay"><?php echo esc_html($age_range); ?></span>
                    </div>
                    <div class="class-card-body">
                        <h3><?php echo esc_html($class_name); ?></h3>
                        <p class="class-card-desc"><?php echo esc_html($description); ?></p>
                        <div class="class-card-cta">
                            <div class="class-pricing">
                                <span class="class-pricing-amount"><?php echo esc_html($price); ?></span>
                                <div class="class-pricing-meta">
                                    <span class="class-pricing-label"><?php echo esc_html($price_label); ?></span>
                                    <span class="class-pricing-note"><?php echo esc_html($price_note); ?></span>
                                </div>
                            </div>
                            <a href="<?php echo esc_url($button_link); ?>" class="btn btn-magenta btn-sm"><?php echo esc_html($button_text); ?></a>
                        </div>
                    </div>
                </article>
            <?php
                endwhile;
            else:
                // Default class cards
                $default_classes = array(
                    array(
                        'name' => 'Tiddler Gym',
                        'age' => '6–12 Months',
                        'desc' => 'A parent-and-baby activity class designed to encourage physical play and interaction between parent and baby. The tactile, bright and stimulating environment changes every week so there\'s always something fresh to explore.',
                        'price' => '£10',
                        'price_label' => 'per class',
                        'price_note' => 'Pay as you go',
                        'button' => 'Details & Book',
                        'link' => '#',
                        'image' => 'class-card-tiddler.png',
                        'modifier' => 'tiddler'
                    ),
                    array(
                        'name' => 'Toddler Gym',
                        'age' => '1–3 Years',
                        'desc' => 'An active exploration class for toddlers with full parent and carer participation. Structured sessions build confidence, coordination and gross motor skills through guided equipment circuits — with a calm cool-down to close.',
                        'price' => '£143',
                        'price_label' => 'per term',
                        'price_note' => '11 sessions',
                        'button' => 'Details & Book',
                        'link' => '#',
                        'image' => 'class-card-toddler.png',
                        'modifier' => 'toddler'
                    ),
                    array(
                        'name' => 'Mini Gym',
                        'age' => '3–4½ Years',
                        'desc' => 'An independent class where children continue learning the foundations of gymnastics. Parents observe from the doorway, with a dedicated watching week at the end of each term. Groups are kept small for maximum focus.',
                        'price' => '£154',
                        'price_label' => 'per term',
                        'price_note' => '11 sessions',
                        'button' => 'Details & Book',
                        'link' => '#',
                        'image' => 'class-card-mini-gym.png',
                        'modifier' => 'minigym'
                    ),
                    array(
                        'name' => 'Gymnastics',
                        'age' => '5+ Years',
                        'desc' => 'Our main programme for children who have progressed from Mini Gym. A progressive badge scheme takes gymnasts from Level 7 through to Diamond, with competition and display opportunities for those who want them.',
                        'price' => 'From £154',
                        'price_label' => 'per term',
                        'price_note' => 'Level dependent',
                        'button' => 'Details',
                        'link' => '#',
                        'image' => 'class-card-gymnastics.png',
                        'modifier' => 'gym'
                    ),
                );
                foreach ($default_classes as $class):
            ?>
                <article class="class-card class-card--<?php echo esc_attr($class['modifier']); ?>">
                    <div class="class-card-img-wrap" aria-hidden="true">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/home/' . $class['image']); ?>" alt="" class="class-card-img" loading="lazy">
                        <span class="age-pill-overlay"><?php echo esc_html($class['age']); ?></span>
                    </div>
                    <div class="class-card-body">
                        <h3><?php echo esc_html($class['name']); ?></h3>
                        <p class="class-card-desc"><?php echo esc_html($class['desc']); ?></p>
                        <div class="class-card-cta">
                            <div class="class-pricing">
                                <span class="class-pricing-amount"><?php echo esc_html($class['price']); ?></span>
                                <div class="class-pricing-meta">
                                    <span class="class-pricing-label"><?php echo esc_html($class['price_label']); ?></span>
                                    <span class="class-pricing-note"><?php echo esc_html($class['price_note']); ?></span>
                                </div>
                            </div>
                            <a href="<?php echo esc_url($class['link']); ?>" class="btn btn-magenta btn-sm"><?php echo esc_html($class['button']); ?></a>
                        </div>
                    </div>
                </article>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php
// Terms and Conditions Section
$terms_title = get_field('terms_title') ?: 'Terms and Conditions on <em>Bookings</em>';
$terms_content = get_field('terms_content');
$terms_date = get_field('terms_date') ?: 'March 2026';
?>

<section class="terms-bookings-section" aria-labelledby="terms-bookings-title">
    <div class="container">
        <article class="terms-bookings-card">
            <span class="section-label"><?php _e('Important Information', 'tjs-gymnastics'); ?></span>
            <h2 id="terms-bookings-title"><?php echo wp_kses_post($terms_title); ?></h2>
            <div class="terms-bookings-body">
                <?php if ($terms_content): ?>
                    <?php echo wp_kses_post($terms_content); ?>
                <?php else: ?>
                    <p>Our classes are run on a termly basis. The classes are coached, structured and progressive classes, the equipment set ups and skills become more demanding the term progresses.</p>
                    <p>We want to work with you and your child to give them the best possible experience at TJ's and we know that it can sometimes take a few weeks for the children to get to know us and what to expect. We also need to ensure the class numbers are limited. Therefore, we prefer that bookings are made for the term. If after few weeks you feel the class is not right for your child, we are happy to refund remaining classes.</p>
                    <p>Trial classes are only available if we have spaces in a particular class. Only 2 trials per term per child can be booked.</p>
                    <p>If you sign up to a trial class, consideration must be given to the fact there is a lot to see and do and, for some, the first class can be quite intense! It can take a few weeks for children to settle into the classes, the first class is not always the best guide for how your child will enjoy our classes.</p>
                    <p>We cannot offer refunds or 'make up' classes for those missed. However, in serious medical circumstances, we can make exceptions to this rule.</p>
                <?php endif; ?>
            </div>
            <p class="terms-bookings-date"><?php echo esc_html($terms_date); ?></p>
        </article>
    </div>
</section>

<?php get_footer(); ?>
