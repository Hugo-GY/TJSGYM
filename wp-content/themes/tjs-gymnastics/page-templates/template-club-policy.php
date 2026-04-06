<?php
/**
 * Template Name: Club Policy Page
 */
get_header();

$hero_eyebrow = get_field('hero_eyebrow') ?: 'Policies & Guidance';
$hero_title = get_field('hero_title') ?: 'Club <em>Policy</em>';
$hero_subtitle = get_field('hero_subtitle') ?: 'Club policies, codes of conduct, and the key rules families need to know before class.';
?>

<section class="page-hero" aria-label="<?php _e('Club policy', 'tjs-gymnastics'); ?>">
    <div class="container">
        <p class="page-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
        <p class="page-hero-sub"><?php echo esc_html($hero_subtitle); ?></p>
    </div>
</section>

<section class="policy-document-section" aria-labelledby="policy-document-title">
    <div class="container">
        <article class="policy-document">
            <span class="section-label"><?php _e('Original Policy Text', 'tjs-gymnastics'); ?></span>
            <h2 id="policy-document-title"><?php _e('Club Policies and Codes of Conduct', 'tjs-gymnastics'); ?></h2>
            <div class="policy-document-body">
                <?php
                $policy_content = get_field('policy_content');
                if ($policy_content):
                    echo wp_kses_post($policy_content);
                else:
                ?>
                    <p><?php _e('We are fully committed to safeguarding and promoting the well-being of all our members. The club believes it is important that members, coaches, administrators and parents associated with the club should, at all times, show respect and understanding for the safety and welfare of others.', 'tjs-gymnastics'); ?></p>
                    <p><?php _e('Therefore, members are encouraged to be open at all times and share any concerns or complaints that they may have about any aspect of the club with our Club Welfare Officer, Nelum Samara,', 'tjs-gymnastics'); ?> <a href="mailto:welfare@tjsgymclub.co.uk">welfare@tjsgymclub.co.uk</a>.</p>
                    <p><?php _e('As a member of TJ\'s Gymnastics Club, Wimbledon you are expected to abide by the following Club Rules. More detail is available in the formal policy documents below.', 'tjs-gymnastics'); ?></p>
                <?php endif; ?>
            </div>
        </article>
    </div>
</section>

<section class="quick-rules-section" aria-labelledby="quick-rules-title">
    <div class="container">
        <header class="quick-rules-header">
            <span class="section-label"><?php _e('For families', 'tjs-gymnastics'); ?></span>
            <h2 id="quick-rules-title"><?php _e('Quick Club Rules', 'tjs-gymnastics'); ?></h2>
            <p><?php _e('A simple summary of the main day-to-day rules that apply across the club.', 'tjs-gymnastics'); ?></p>
        </header>
        
        <ul class="quick-rules-grid" role="list">
            <?php
            if (have_rows('quick_rules')):
                while (have_rows('quick_rules')): the_row();
                    $rule_title = get_sub_field('rule_title');
                    $rule_description = get_sub_field('rule_description');
            ?>
                <li class="policy-rule-item">
                    <h3><?php echo esc_html($rule_title); ?></h3>
                    <p><?php echo esc_html($rule_description); ?></p>
                </li>
            <?php
                endwhile;
            else:
                $default_rules = array(
                    array('title' => 'Attire, hair and jewellery', 'desc' => 'Gymnasts must wear suitable attire for training, keep long hair tied back, and remove all body jewellery.'),
                    array('title' => 'Fees paid promptly', 'desc' => 'Fees must be paid promptly for all classes.'),
                    array('title' => 'British Gymnastics registration', 'desc' => 'All gymnasts must be registered members of British Gymnastics.'),
                    array('title' => 'Report injuries or illness before warm-up', 'desc' => 'Members must inform the head coach about any injuries or illness before the warm-up begins.'),
                    array('title' => 'No eating, bring a water bottle', 'desc' => 'Gymnasts must not eat during a session and should bring a water bottle to leave in the designated area.'),
                    array('title' => 'Parent or guardian collection', 'desc' => 'All children should remain with coaches at the end of a session until collected by their parent or guardian.'),
                    array('title' => 'Parent access rules', 'desc' => 'With the exception of Toddler Gym classes and Watching Week, parents are not allowed into the hall and should use the waiting area or front lobby.'),
                    array('title' => 'Watching week', 'desc' => 'Watching week for all classes will always be the last week of term.'),
                    array('title' => 'Filming and photography restrictions', 'desc' => 'Filming and photography are only allowed during Watching Week, except in Toddler Gym where photos can be taken with the coach\'s consent.'),
                );
                foreach ($default_rules as $rule):
            ?>
                <li class="policy-rule-item">
                    <h3><?php echo esc_html($rule['title']); ?></h3>
                    <p><?php echo esc_html($rule['desc']); ?></p>
                </li>
            <?php endforeach; endif; ?>
        </ul>
    </div>
</section>

<section class="policy-downloads-section" aria-labelledby="policy-downloads-title">
    <div class="container">
        <header class="policy-downloads-header">
            <span class="section-label"><?php _e('Download Policies', 'tjs-gymnastics'); ?></span>
            <h2 id="policy-downloads-title"><?php _e('Policy Downloads', 'tjs-gymnastics'); ?></h2>
            <p><?php _e('Download the formal policy documents as PDFs.', 'tjs-gymnastics'); ?></p>
        </header>
        
        <ul class="policy-downloads-list" role="list">
            <?php
            if (have_rows('policy_downloads')):
                while (have_rows('policy_downloads')): the_row();
                    $download_title = get_sub_field('download_title');
                    $download_desc = get_sub_field('download_description');
                    $download_url = get_sub_field('download_url');
            ?>
                <li class="policy-download-item">
                    <div class="policy-download-copy">
                        <h3><?php echo esc_html($download_title); ?></h3>
                        <p><?php echo esc_html($download_desc); ?></p>
                    </div>
                    <a class="btn btn-ghost-magenta btn-sm" href="<?php echo esc_url($download_url); ?>" target="_blank" rel="noopener"><?php _e('Download PDF', 'tjs-gymnastics'); ?></a>
                </li>
            <?php
                endwhile;
            else:
                $default_downloads = array(
                    array('title' => 'Child Safeguarding & Protection Policy', 'desc' => 'Safeguarding guidance and child protection procedures.', 'url' => 'https://www.tjsgymclub.co.uk/wp-content/uploads/2021/01/v2-Safeguarding-Child-Protection-Policy.pdf'),
                    array('title' => 'Code of Conduct & Club Rules For Parents & Gymnasts', 'desc' => 'The main conduct document for families and gymnasts.', 'url' => 'https://www.tjsgymclub.co.uk/wp-content/uploads/2021/01/v2-Code-of-Conduct-Club-Rules-or-Parents-Gymnasts.pdf'),
                    array('title' => 'Coach Code Of Conduct Policy', 'desc' => 'Standards and conduct expectations for coaching staff.', 'url' => 'https://www.tjsgymclub.co.uk/wp-content/uploads/2021/01/v2-Coach-Code-of-Conduct-Policy.pdf'),
                    array('title' => 'Anti Bullying Policy', 'desc' => 'The club\'s anti-bullying approach and reporting framework.', 'url' => 'https://www.tjsgymclub.co.uk/wp-content/uploads/2021/01/Anti-Bullying-Policy.pdf'),
                    array('title' => 'Equity Policy', 'desc' => 'Fairness, access, and inclusion guidance across the club.', 'url' => 'https://www.tjsgymclub.co.uk/wp-content/uploads/2021/01/Equity-Policy.pdf'),
                );
                foreach ($default_downloads as $download):
            ?>
                <li class="policy-download-item">
                    <div class="policy-download-copy">
                        <h3><?php echo esc_html($download['title']); ?></h3>
                        <p><?php echo esc_html($download['desc']); ?></p>
                    </div>
                    <a class="btn btn-ghost-magenta btn-sm" href="<?php echo esc_url($download['url']); ?>" target="_blank" rel="noopener"><?php _e('Download PDF', 'tjs-gymnastics'); ?></a>
                </li>
            <?php endforeach; endif; ?>
        </ul>
    </div>
</section>

<?php get_footer(); ?>
