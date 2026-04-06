<?php
/**
 * Template Name: History Page
 */
get_header();

$hero_eyebrow = get_field('hero_eyebrow') ?: 'Since 1988';
$hero_title = get_field('hero_title') ?: 'Our <em>History</em>';
$hero_subtitle = get_field('hero_subtitle') ?: 'A few of the key moments that shaped TJ\'s, from its beginnings in 1988 to its new home in Raynes Park.';
?>

<section class="page-hero">
    <div class="container">
        <p class="page-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
        <p class="page-hero-sub"><?php echo esc_html($hero_subtitle); ?></p>
    </div>
</section>

<section class="history-timeline-section section" aria-labelledby="timeline-heading">
    <div class="container">
        <h2 id="timeline-heading" class="history-timeline-title sr-only"><?php _e('Timeline', 'tjs-gymnastics'); ?></h2>
        
        <div class="history-timeline">
            <?php
            if (have_rows('timeline_items')):
                while (have_rows('timeline_items')): the_row();
                    $year = get_sub_field('year');
                    $title = get_sub_field('title');
                    $content = get_sub_field('content');
            ?>
                <article class="history-timeline-item">
                    <p class="history-timeline-year"><?php echo esc_html($year); ?></p>
                    <h3><?php echo esc_html($title); ?></h3>
                    <p class="history-timeline-copy"><?php echo esc_html($content); ?></p>
                </article>
            <?php
                endwhile;
            else:
                $default_timeline = array(
                    array('year' => '1988', 'title' => 'TJ\'s opens', 'content' => 'TJ\'s opened in September 1988. Gill Humby was leaving Roehampton University with a BSc in Sports Studies, wondering what employment she could find. Tricia and Joanna wrote an open letter to the Sports Department looking for a Gymnastics coach to run the children\'s gym club they wanted to open using the hall they rented for Aerobic classes. Gill gave them a call and TJ\'s was born!'),
                    array('year' => 'Growth years', 'title' => 'More coaches, more classes', 'content' => 'For a number of years Gill ran TJ\'s alone, but as the club became more popular and numbers increased it was clear more coaches and classes would be required. The timetable expanded, the staff team grew, and TJ\'s steadily developed into the welcoming club families know today.'),
                    array('year' => '1997', 'title' => 'Natalie joins the team', 'content' => 'Gill asked Natalie to join the team in a more permanent role in 1997, and she continues to be a very valued support in the running of the club.'),
                    array('year' => '1997–2005', 'title' => 'Coach and parent', 'content' => 'Gill married Chris in October 1997 and they have two sons, Max and Samuel. In 2005 they moved from Morden to Elstead in Surrey. Natalie married Paul in 2004 and they have a son and daughter, Jack and Lucy. All four children attended TJ\'s, so both Gill and Natalie have known the club as coaches and as parents.'),
                    array('year' => '2023', 'title' => 'A new home in Raynes Park', 'content' => 'In January 2023 TJ\'s moved from the hall in Wimbledon, which we had hired for 34 years, to Raynes Park Sports Pavilion, SW20. At the same time, Jade joined the senior coaching team to assist Gill and Natalie. We are very proud of TJ\'s: a small, friendly and caring club where some of the first children we taught have grown up, stayed involved in gymnastics, and now even bring their own children to TJ\'s.'),
                );
                foreach ($default_timeline as $item):
            ?>
                <article class="history-timeline-item">
                    <p class="history-timeline-year"><?php echo esc_html($item['year']); ?></p>
                    <h3><?php echo esc_html($item['title']); ?></h3>
                    <p class="history-timeline-copy"><?php echo esc_html($item['content']); ?></p>
                </article>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<section class="history-gallery section" aria-labelledby="gallery-heading">
    <div class="container">
        <div class="history-section-head">
            <span class="section-label"><?php _e('Archive moments', 'tjs-gymnastics'); ?></span>
            <h2 id="gallery-heading"><?php _e('A Look Back Through the Years', 'tjs-gymnastics'); ?></h2>
        </div>
        
        <div class="history-gallery-grid">
            <?php
            if (have_rows('gallery_images')):
                while (have_rows('gallery_images')): the_row();
                    $image = get_sub_field('image');
                    $caption = get_sub_field('caption');
                    if ($image):
            ?>
                <figure class="history-gallery-item">
                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                    <figcaption>
                        <p><?php echo esc_html($caption); ?></p>
                    </figcaption>
                </figure>
            <?php 
                    endif;
                endwhile;
            else:
                $default_gallery = array(
                    array('img' => 'image_08.jpg', 'caption' => 'Max growing up as part of the TJ\'s story.'),
                    array('img' => '99 Naomi.jpg', 'caption' => 'Naomi in one of TJ\'s cherished archive moments.'),
                    array('img' => '103 Lucy.jpg', 'caption' => 'Lucy enjoying one of TJ\'s archive class moments.'),
                    array('img' => 'image_06.jpg', 'caption' => 'Children building confidence through play and movement.'),
                    array('img' => 'image_02.jpg', 'caption' => 'Gill at a TJ\'s anniversary celebration.'),
                    array('img' => 'image_03.jpg', 'caption' => 'Former gymnasts and team members celebrating together.'),
                    array('img' => 'image_04.jpg', 'caption' => 'Marking 20 years of TJ\'s with the club community.'),
                    array('img' => 'image_05.jpg', 'caption' => 'Another special anniversary moment from the TJ\'s journey.'),
                );
                foreach ($default_gallery as $item):
            ?>
                <figure class="history-gallery-item">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/history/' . $item['img']); ?>" alt="<?php echo esc_attr($item['caption']); ?>">
                    <figcaption>
                        <p><?php echo esc_html($item['caption']); ?></p>
                    </figcaption>
                </figure>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
