<?php
if (PHP_SAPI !== 'cli') {
    exit("CLI only\n");
}

require dirname(__DIR__, 4) . '/wp-load.php';

$legacy_slugs = array(
    'toddler-gym-booking',
    'tiddler-gym-booking',
    'mini-gym-booking',
    'toddler-gym-confirmation',
    'tiddler-gym-confirmation',
    'mini-gym-confirmation',
);

foreach ($legacy_slugs as $slug) {
    $page = get_page_by_path($slug, OBJECT, 'page');

    if (!$page) {
        echo "Missing or already retired: {$slug}\n";
        continue;
    }

    if ($page->post_status === 'trash') {
        echo "Already in trash: {$slug} (#{$page->ID})\n";
        continue;
    }

    $trashed = wp_trash_post($page->ID);

    if ($trashed) {
        echo "Trashed: {$slug} (#{$page->ID})\n";
        continue;
    }

    echo "Failed to trash: {$slug} (#{$page->ID})\n";
}