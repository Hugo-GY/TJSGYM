<?php
/**
 * Template Name: Coaches Page
 */
get_header();

// Get coaches data from ACF fields or fallback to default
$hero_eyebrow = get_field('hero_eyebrow') ?: 'The people behind the magic';
$hero_title = get_field('hero_title') ?: 'Meet Our <em>Coaches</em>';
$hero_subtitle = get_field('hero_subtitle') ?: 'A passionate, close-knit team — many of whom started at TJ\'s as children themselves. Together we bring over a century of gymnastics experience to every single class.';
?>

<!-- ── Page Hero ──────────────────────────────────── -->
<section class="page-hero">
    <div class="container">
        <p class="page-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>
        <h1><?php echo wp_kses_post($hero_title); ?></h1>
        <p class="page-hero-sub"><?php echo esc_html($hero_subtitle); ?></p>
    </div>
</section>

<?php
// Lead Team Section
$lead_team_label = get_field('lead_team_label') ?: 'The lead team';
$lead_team_title = get_field('lead_team_title') ?: 'Heading Up the Club';
?>

<!-- ── Lead Team ──────────────────────────────────── -->
<section class="coaches-section" aria-labelledby="lead-heading">
    <div class="container">
        <div class="section-intro">
            <span class="section-intro-label"><?php echo esc_html($lead_team_label); ?></span>
            <h2 id="lead-heading"><?php echo esc_html($lead_team_title); ?></h2>
        </div>
        <div class="coaches-grid-lead">
            <?php
            if (have_rows('lead_coaches')):
                while (have_rows('lead_coaches')): the_row();
                    $name = get_sub_field('name');
                    $role = get_sub_field('role');
                    $badge = get_sub_field('badge');
                    $bio = get_sub_field('bio');
                    $photo = get_sub_field('photo');
            ?>
                <div class="coach-card">
                    <div class="coach-photo-wrap coach-photo-wrap--lead">
                        <?php if ($photo): ?>
                            <img src="<?php echo esc_url($photo['url']); ?>" alt="<?php echo esc_attr($name); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="coach-body">
                        <h3 class="coach-name coach-name--lg"><?php echo esc_html($name); ?></h3>
                        <p class="coach-role"><?php echo esc_html($role); ?></p>
                        <?php if ($badge): ?>
                            <span class="coach-badge"><?php echo esc_html($badge); ?></span>
                        <?php endif; ?>
                        <p class="coach-bio"><?php echo esc_html($bio); ?></p>
                    </div>
                </div>
            <?php
                endwhile;
            else:
                // Default coaches data
                $default_lead_coaches = array(
                    array('name' => 'Gill Holland', 'role' => 'General Manager & Head Coach', 'badge' => 'Level 3 · General & Pre-school', 'bio' => 'Gill founded TJ\'s in September 1988 and has been running it for 36 wonderful years. She holds Level 3 qualifications in both General and Pre-school Club Coaching, and it\'s a joy seeing former gymnasts now bringing their own children through the door.', 'photo' => '01_Gill_Holland.jpg'),
                    array('name' => 'Natalie Garlick', 'role' => 'Head of Gymnastics & SEN Co-ordinator', 'badge' => 'Level 3 · General & Pre-school', 'bio' => 'Coached by Gill as a teenager, Natalie has been part of the TJ\'s team since 1997. She leads the general gymnastics programme, heads up our competition and display teams, and is our Special Educational Needs Co-ordinator.', 'photo' => '02_Natalie_Garlick.jpg'),
                    array('name' => 'Jade Shaw', 'role' => 'Deputy Head Coach', 'badge' => 'Level 2 UKCC · Pre-school Add-on', 'bio' => 'Jade first came to TJ\'s aged one in Toddler Gym and progressed all the way to our Wednesday squad — the ultimate TJ\'s story! She took on the Deputy Head Coach role in January 2023. Her two daughters, Kyla and Nyrah, attend the club too.', 'photo' => '03_Jade_Shaw.jpg'),
                    array('name' => 'Nelum Samara', 'role' => 'Safeguarding & Welfare Officer', 'badge' => '', 'bio' => 'Coached by Gill during her school days, Nelum reconnected with TJ\'s via social media years later. She joined as Safeguarding and Welfare Officer in September 2024 and is thrilled to be actively involved in the sport she loves once more.', 'photo' => '04_Nelum_Samara_Safeguarding_and_Welfare_Officer.jpeg'),
                );
                foreach ($default_lead_coaches as $coach):
            ?>
                <div class="coach-card">
                    <div class="coach-photo-wrap coach-photo-wrap--lead">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/coaches/' . $coach['photo']); ?>" alt="<?php echo esc_attr($coach['name']); ?>">
                    </div>
                    <div class="coach-body">
                        <h3 class="coach-name coach-name--lg"><?php echo esc_html($coach['name']); ?></h3>
                        <p class="coach-role"><?php echo esc_html($coach['role']); ?></p>
                        <?php if ($coach['badge']): ?>
                            <span class="coach-badge"><?php echo esc_html($coach['badge']); ?></span>
                        <?php endif; ?>
                        <p class="coach-bio"><?php echo esc_html($coach['bio']); ?></p>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php
// Coaches & Assistants Section
$coaches_label = get_field('coaches_label') ?: 'The coaching team';
$coaches_title = get_field('coaches_title') ?: 'Coaches & Assistants';
?>

<!-- ── Coaches & Assistants ───────────────────────── -->
<section class="coaches-section" aria-labelledby="coaches-heading">
    <div class="container">
        <div class="section-intro">
            <span class="section-intro-label"><?php echo esc_html($coaches_label); ?></span>
            <h2 id="coaches-heading"><?php echo esc_html($coaches_title); ?></h2>
        </div>
        <div class="coaches-grid-std">
            <?php
            if (have_rows('assistant_coaches')):
                while (have_rows('assistant_coaches')): the_row();
                    $name = get_sub_field('name');
                    $bio = get_sub_field('bio');
                    $photo = get_sub_field('photo');
            ?>
                <div class="coach-card">
                    <div class="coach-photo-wrap coach-photo-wrap--std">
                        <?php if ($photo): ?>
                            <img src="<?php echo esc_url($photo['url']); ?>" alt="<?php echo esc_attr($name); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="coach-body coach-body--sm">
                        <h3 class="coach-name coach-name--sm"><?php echo esc_html($name); ?></h3>
                        <p class="coach-bio coach-bio--sm"><?php echo esc_html($bio); ?></p>
                    </div>
                </div>
            <?php
                endwhile;
            else:
                $default_assistants = array(
                    array('name' => 'Hazel Pithers', 'bio' => 'Hazel joined the coaching team in January 2023. She holds her Level 2 coaching qualification and is training to qualify for stunt work.', 'photo' => '05_Hazel_Pithers.jpg'),
                    array('name' => 'JV Mattei', 'bio' => 'JV was introduced to TJ\'s when his daughter joined Mini Gym in January 2024. A passionate Street Dance coach, he began assisting at TJ\'s straight away and is loving being part of the team. He holds gymnastics instructor qualification.', 'photo' => '07_JV_Mattei.png'),
                    array('name' => 'Nadine Henry', 'bio' => 'Coached by Natalie as a child, Nadine reconnected with TJ\'s through a social event. She is a qualified physio therapist and holds the Level-1 Assistant qualification.', 'photo' => '08_Nadine_Henry.jpg'),
                    array('name' => 'Hannah Pettit', 'bio' => 'Hannah was coached at school by Gill and later joined TJ\'s Toddler Gym with her daughter. Mum of three, she returned to coaching in January 2023 and assists our Mini Gym classes. She holds her Level 1 General Gymnastics qualification.', 'photo' => '06_Hannah_Pettit.jpg'),
                );
                foreach ($default_assistants as $coach):
            ?>
                <div class="coach-card">
                    <div class="coach-photo-wrap coach-photo-wrap--std">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/coaches/' . $coach['photo']); ?>" alt="<?php echo esc_attr($coach['name']); ?>">
                    </div>
                    <div class="coach-body coach-body--sm">
                        <h3 class="coach-name coach-name--sm"><?php echo esc_html($coach['name']); ?></h3>
                        <p class="coach-bio coach-bio--sm"><?php echo esc_html($coach['bio']); ?></p>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php
// Junior Helpers Section
$helpers_label = get_field('helpers_label') ?: 'The next generation';
$helpers_title = get_field('helpers_title') ?: 'Our Junior Helpers';
?>

<!-- ── Junior Helpers ─────────────────────────────── -->
<section class="coaches-section" aria-labelledby="helpers-heading">
    <div class="container">
        <div class="section-intro">
            <span class="section-intro-label"><?php echo esc_html($helpers_label); ?></span>
            <h2 id="helpers-heading"><?php echo esc_html($helpers_title); ?></h2>
        </div>
        <div class="coaches-grid-std">
            <?php
            if (have_rows('junior_helpers')):
                while (have_rows('junior_helpers')): the_row();
                    $name = get_sub_field('name');
                    $bio = get_sub_field('bio');
                    $photo = get_sub_field('photo');
            ?>
                <div class="coach-card">
                    <div class="coach-photo-wrap coach-photo-wrap--std">
                        <?php if ($photo): ?>
                            <img src="<?php echo esc_url($photo['url']); ?>" alt="<?php echo esc_attr($name); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="coach-body coach-body--sm">
                        <h3 class="coach-name coach-name--sm"><?php echo esc_html($name); ?></h3>
                        <p class="coach-bio coach-bio--sm"><?php echo esc_html($bio); ?></p>
                    </div>
                </div>
            <?php
                endwhile;
            else:
                // Default helpers data - matching demo page
                $default_helpers = array(
                    array('name' => 'Bea Gray', 'bio' => 'Bea was a member of our Elite Squad until December 2022, assisted for her Duke of Edinburgh award, and has chosen to continue coaching. A keen rugby player and Ball Girl at Wimbledon 2024. Holds the Gymnastics Helper award.', 'photo' => '11_Bea_Gray.jpg'),
                    array('name' => 'Maya Francis', 'bio' => 'Maya was a member of our Elite Squad until April 2024 and has now chosen to focus on coaching. A keen dancer and Year 10 student, she holds the Gymnastics Helper award.', 'photo' => '12_Maya_Francis.jpg'),
                    array('name' => 'Cara Kelly', 'bio' => 'Cara also started TJ\'s aged 1! She was a member of our Girls\' Squad until July 2024 and has now chosen to focus on coaching. A Year 10 student, she holds the Gymnastics Helper award.', 'photo' => '13_Cara_Kelly.jpg'),
                    array('name' => 'Mio Nakashima', 'bio' => 'Mio was a member of our Elite Squad until December 2022 and assisted for her Duke of Edinburgh award. In April 2024 she left to study for a year in Japan — we hope to see her back in 2025!', 'photo' => '14_Mio_Nakashima.jpg'),
                    array('name' => 'Emily Runeckles', 'bio' => 'Emily started in our Tiddler class along with her twin brother. Now an A level student, she enjoys coming to coach once a week. She holds the Gymnastics Helper award.', 'photo' => 'Emily.jpeg'),
                    array('name' => 'Phoebe Dunnett', 'bio' => 'Phoebe first attended TJ\'s in the Toddler Gym and continued through to the Gym squad. She recently decided to focus on coaching. She holds the Gymnastic Helper award and is a member of the school lacrosse, netball and athletics teams.', 'photo' => 'Phoebe.jpg'),
                    array('name' => 'Jess Primrose', 'bio' => 'Jess first attended TJ\'s in the Toddler Gym and continued through to the Gym Squad. She recently decided to focus on her cheerleading, which meant she was able to start coaching with us. She also holds her Gymnastics Helper award.', 'photo' => 'Jess.jpeg'),
                    array('name' => 'Lucy Garlick', 'bio' => 'Natalie\'s daughter! Lucy is a member of the GB Climbing squad and has competed in the USA, Russia, South Korea and China. She is not currently coaching regularly but will return when available, and she holds the Gymnastics Helper award.', 'photo' => '10_Lucy_Garlick.jpg'),
                );
                foreach ($default_helpers as $coach):
                    $is_placeholder = isset($coach['placeholder']) && $coach['placeholder'];
                    $initials = '';
                    if ($is_placeholder) {
                        $name_parts = explode(' ', $coach['name']);
                        foreach ($name_parts as $part) {
                            $initials .= strtoupper(substr($part, 0, 1));
                        }
                    }
            ?>
                <div class="coach-card<?php echo $is_placeholder ? ' coach-card--placeholder' : ''; ?>">
                    <div class="coach-photo-wrap coach-photo-wrap--std<?php echo $is_placeholder ? ' coach-photo-wrap--placeholder' : ''; ?>"<?php echo $is_placeholder ? ' aria-hidden="true"' : ''; ?>>
                        <?php if ($is_placeholder): ?>
                            <span class="coach-placeholder-initials"><?php echo esc_html($initials); ?></span>
                        <?php else: ?>
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/coaches/' . $coach['photo']); ?>" alt="<?php echo esc_attr($coach['name']); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="coach-body coach-body--sm">
                        <h3 class="coach-name coach-name--sm"><?php echo esc_html($coach['name']); ?></h3>
                        <p class="coach-bio coach-bio--sm"><?php echo esc_html($coach['bio']); ?></p>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
