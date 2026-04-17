<?php
if (PHP_SAPI !== 'cli') {
    exit("CLI only\n");
}

require dirname(__DIR__, 4) . '/wp-load.php';

if (!class_exists('WooCommerce')) {
    fwrite(STDERR, "WooCommerce is not active.\n");
    exit(1);
}

function tjs_seed_find_product_id_by_slug($slug) {
    $product_post = get_page_by_path($slug, OBJECT, 'product');
    return $product_post ? intval($product_post->ID) : 0;
}

function tjs_seed_update_product_meta($product_id, array $values, array $field_keys) {
    foreach ($values as $meta_key => $meta_value) {
        update_post_meta($product_id, $meta_key, $meta_value);
        if (isset($field_keys[$meta_key])) {
            update_post_meta($product_id, '_' . $meta_key, $field_keys[$meta_key]);
        }
    }
}

function tjs_seed_ensure_variable_product(array $spec) {
    $product_id = tjs_seed_find_product_id_by_slug($spec['slug']);

    if ($product_id > 0) {
        $product = new WC_Product_Variable($product_id);
    } else {
        $product = new WC_Product_Variable();
    }

    $product->set_name($spec['name']);
    $product->set_slug($spec['slug']);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_description($spec['description']);
    $product->set_short_description($spec['short_description']);
    $product->set_sku($spec['sku']);
    $product->set_manage_stock(false);
    $product->set_menu_order($spec['menu_order']);

    $attributes = array();
    foreach ($spec['attributes'] as $index => $attribute_spec) {
        $attribute = new WC_Product_Attribute();
        $attribute->set_id(0);
        $attribute->set_name($attribute_spec['label']);
        $attribute->set_options($attribute_spec['options']);
        $attribute->set_position($index);
        $attribute->set_visible(true);
        $attribute->set_variation(true);
        $attributes[] = $attribute;
    }

    $product->set_attributes($attributes);
    $product->save();

    $product_id = $product->get_id();

    wp_set_object_terms($product_id, array($spec['category_slug']), 'product_cat', false);
    wp_set_object_terms($product_id, 'variable', 'product_type', false);

    tjs_seed_update_product_meta($product_id, $spec['acf_values'], $spec['acf_field_keys']);

    $variation_ids = array();

    foreach ($spec['sessions'] as $index => $session) {
        $variation_id = wc_get_product_id_by_sku($session['sku']);
        $variation = null;

        if ($variation_id > 0) {
            $variation = wc_get_product($variation_id);
            if (!$variation || !$variation->is_type('variation') || intval($variation->get_parent_id()) !== $product_id) {
                $variation = null;
                $variation_id = 0;
            }
        }

        if (!$variation) {
            $variation = new WC_Product_Variation();
            $variation->set_parent_id($product_id);
        }

        $variation->set_status('publish');
        $variation->set_menu_order($index + 1);
        $variation->set_regular_price((string) $session['price']);
        $variation->set_price((string) $session['price']);
        $variation->set_manage_stock(true);
        $variation->set_stock_quantity($session['stock']);
        $variation->set_stock_status($session['stock'] > 0 ? 'instock' : 'outofstock');
        $variation->set_sku($session['sku']);

        $variation_attributes = array(
            'class-day' => $session['day'],
            'time-slot' => $session['time'],
        );

        if (!empty($session['group'])) {
            $variation_attributes['group-level'] = $session['group'];
        }

        $variation->set_attributes($variation_attributes);
        $variation->save();

        $variation_id = $variation->get_id();
        update_post_meta($variation_id, '_tjs_class_capacity', $session['capacity']);

        $variation_ids[] = $variation_id;
    }

    WC_Product_Variable::sync($product_id);
    wc_delete_product_transients($product_id);

    if (function_exists('tjs_update_parent_stock')) {
        tjs_update_parent_stock($product_id);
    }
    if (function_exists('tjs_clear_product_cache')) {
        tjs_clear_product_cache($spec['slug']);
    }

    return array(
        'product_id' => $product_id,
        'variation_ids' => $variation_ids,
    );
}

$common_field_keys = array(
    'pay_type' => 'field_class_pay_type',
    'enable_trial' => 'field_enable_trial',
    'trial_price' => 'field_trial_price',
);

$mini_field_keys = array_merge($common_field_keys, array(
    'ct_season' => 'field_mini_ct_season',
    'ct_status' => 'field_mini_ct_status',
    'ct_weeks' => 'field_mini_ct_weeks',
    'ct_dates' => 'field_mini_ct_dates',
    'ct_halfterm' => 'field_mini_ct_halfterm',
    'ct_payment_due' => 'field_mini_ct_payment_due',
    'nt1_season' => 'field_mini_nt1_season',
    'nt1_status' => 'field_mini_nt1_status',
    'nt1_weeks' => 'field_mini_nt1_weeks',
    'nt1_dates' => 'field_mini_nt1_dates',
    'nt1_halfterm' => 'field_mini_nt1_halfterm',
    'nt1_payment_due' => 'field_mini_nt1_payment_due',
    'nt2_season' => 'field_mini_nt2_season',
    'nt2_status' => 'field_mini_nt2_status',
    'nt2_weeks' => 'field_mini_nt2_weeks',
    'nt2_dates' => 'field_mini_nt2_dates',
    'nt2_halfterm' => 'field_mini_nt2_halfterm',
    'nt2_payment_due' => 'field_mini_nt2_payment_due',
    'age_range' => 'field_mini_age_range',
    'about_title' => 'field_mini_about_title',
    'about_content' => 'field_mini_about_content',
));

$gym_field_keys = array_merge($common_field_keys, array(
    'ct_season' => 'field_gym_ct_season',
    'ct_status' => 'field_gym_ct_status',
    'ct_weeks' => 'field_gym_ct_weeks',
    'ct_dates' => 'field_gym_ct_dates',
    'ct_halfterm' => 'field_gym_ct_halfterm',
    'ct_payment_due' => 'field_gym_ct_payment_due',
    'nt1_season' => 'field_gym_nt1_season',
    'nt1_status' => 'field_gym_nt1_status',
    'nt1_weeks' => 'field_gym_nt1_weeks',
    'nt1_dates' => 'field_gym_nt1_dates',
    'nt1_halfterm' => 'field_gym_nt1_halfterm',
    'nt1_payment_due' => 'field_gym_nt1_payment_due',
    'nt2_season' => 'field_gym_nt2_season',
    'nt2_status' => 'field_gym_nt2_status',
    'nt2_weeks' => 'field_gym_nt2_weeks',
    'nt2_dates' => 'field_gym_nt2_dates',
    'nt2_halfterm' => 'field_gym_nt2_halfterm',
    'nt2_payment_due' => 'field_gym_nt2_payment_due',
    'age_range' => 'field_gym_age_range',
    'about_title' => 'field_gym_about_title',
    'about_content' => 'field_gym_about_content',
));

$specs = array(
    array(
        'name' => 'Mini Gym',
        'slug' => 'mini-gym-product',
        'sku' => 'MINI-GYM-001',
        'category_slug' => 'mini-gym',
        'menu_order' => 30,
        'short_description' => 'The Mini Gym is an independent class for children aged 3 to 4 years. Parents and carers do not come into the hall, but the doorways remain open to allow viewing. The last week of each term is our Watching Week, when families can come into the hall to watch the class.',
        'description' => '<p>The Mini Gym is an independent class for children aged 3 to 4 years. Parents and carers do not come into the hall, but the doorways remain open to allow viewing. The last week of each term is our Watching Week, when families can come into the hall to watch the class.</p><p>We take a different theme each week and start with a short warm-up using music and language that emphasises that theme. The equipment is set up in three circuits, rather than one large circuit as for the toddlers, and after the warm-up the children move into three groups to use circuits built around the same idea. Each week the set-up changes, and as the term progresses the equipment continues to challenge every child.</p><p>Each group has its own coach and a maximum of five children. While our first focus is fun, Mini Gym also helps children continue learning the basics of rolling, handstands, springboard use, vaulting, simple bar work, co-ordination, balance, jumping, strength and flexibility. To close the class, all groups join together for a short discussion about what they have been doing, what they liked, what they found easy or hard, and an action song.</p>',
        'attributes' => array(
            array(
                'label' => 'Class Day',
                'options' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday'),
            ),
            array(
                'label' => 'Time Slot',
                'options' => array('1:20 – 2:00', '2:10 – 2:50', '10:30 – 11:10'),
            ),
        ),
        'acf_field_keys' => $mini_field_keys,
        'acf_values' => array(
            'ct_season' => 'Summer 2026',
            'ct_status' => 'Teaching now',
            'ct_weeks' => '13 weeks',
            'ct_dates' => "13 Apr – 21 May\n1 Jun – 16 Jul",
            'ct_halfterm' => 'Half term: w/k 25 May · No class 4 May',
            'ct_payment_due' => 'Payment due by 12 March',
            'nt1_season' => 'Winter 2026',
            'nt1_status' => 'Next term',
            'nt1_weeks' => '12 weeks',
            'nt1_dates' => "7 Sep – 15 Oct\n2 Nov – 10 Dec",
            'nt1_halfterm' => '2-week half term: w/k 19 October',
            'nt1_payment_due' => 'Payment due by 26 June',
            'nt2_season' => 'Spring 2027',
            'nt2_status' => 'Planning ahead',
            'nt2_weeks' => '11 weeks',
            'nt2_dates' => "4 Jan – 11 Feb\n22 Feb – 25 Mar",
            'nt2_halfterm' => 'Half term: w/k 15 February',
            'nt2_payment_due' => 'Payment due by 27 November',
            'age_range' => '3–4½ Years',
            'about_title' => 'Independent gymnastics with <em>themes</em>, circuits and confidence',
            'about_content' => '<p>The Mini Gym is an independent class for children aged 3 to 4 years. Parents and carers do not come into the hall, but the doorways remain open to allow viewing. The last week of each term is our Watching Week, when families can come into the hall to watch the class.</p><p>We take a different theme each week and start with a short warm-up using music and language that emphasises that theme. The equipment is set up in three circuits, rather than one large circuit as for the toddlers, and after the warm-up the children move into three groups to use circuits built around the same idea. Each week the set-up changes, and as the term progresses the equipment continues to challenge every child.</p><p>Each group has its own coach and a maximum of five children. While our first focus is fun, Mini Gym also helps children continue learning the basics of rolling, handstands, springboard use, vaulting, simple bar work, co-ordination, balance, jumping, strength and flexibility. To close the class, all groups join together for a short discussion about what they have been doing, what they liked, what they found easy or hard, and an action song.</p>',
            'pay_type' => 'per_term',
            'enable_trial' => 0,
            'trial_price' => '',
        ),
        'sessions' => array(
            array('sku' => 'MINI-GYM-MON-1320', 'day' => 'Monday', 'time' => '1:20 – 2:00', 'group' => '', 'price' => 168, 'stock' => 2, 'capacity' => 15),
            array('sku' => 'MINI-GYM-MON-1410', 'day' => 'Monday', 'time' => '2:10 – 2:50', 'group' => '', 'price' => 168, 'stock' => 2, 'capacity' => 15),
            array('sku' => 'MINI-GYM-TUE-1320', 'day' => 'Tuesday', 'time' => '1:20 – 2:00', 'group' => '', 'price' => 182, 'stock' => 2, 'capacity' => 15),
            array('sku' => 'MINI-GYM-TUE-1410', 'day' => 'Tuesday', 'time' => '2:10 – 2:50', 'group' => '', 'price' => 182, 'stock' => 3, 'capacity' => 15),
            array('sku' => 'MINI-GYM-WED-1030', 'day' => 'Wednesday', 'time' => '10:30 – 11:10', 'group' => '', 'price' => 182, 'stock' => 2, 'capacity' => 15),
            array('sku' => 'MINI-GYM-WED-1320', 'day' => 'Wednesday', 'time' => '1:20 – 2:00', 'group' => '', 'price' => 182, 'stock' => 3, 'capacity' => 15),
            array('sku' => 'MINI-GYM-WED-1410', 'day' => 'Wednesday', 'time' => '2:10 – 2:50', 'group' => '', 'price' => 182, 'stock' => 3, 'capacity' => 15),
            array('sku' => 'MINI-GYM-THU-1320', 'day' => 'Thursday', 'time' => '1:20 – 2:00', 'group' => '', 'price' => 182, 'stock' => 0, 'capacity' => 15),
            array('sku' => 'MINI-GYM-THU-1410', 'day' => 'Thursday', 'time' => '2:10 – 2:50', 'group' => '', 'price' => 182, 'stock' => 1, 'capacity' => 15),
        ),
    ),
    array(
        'name' => 'Gymnastics',
        'slug' => 'gymnastics-product',
        'sku' => 'GYMNASTICS-001',
        'category_slug' => 'gymnastics',
        'menu_order' => 40,
        'short_description' => 'Our Gymnastics classes are for children aged 5 years. Sessions are built around floor and vault work, helping gymnasts develop confidence, technique, body control and strength in a structured but encouraging environment.',
        'description' => '<p>Our Gymnastics classes are for children aged 5 years. Sessions are built around floor and vault work, helping gymnasts develop confidence, technique, body control and strength in a structured but encouraging environment.</p><p>We use our own badge progression system, moving from Levels 7 to 1 and then on to Bronze, Silver, Gold, Platinum and Diamond awards.</p><p>We are limited with times and space as we accommodate a large age range after school. Children need to have attended a preschool class to be eligible for our Gymnastics classes. Anyone attending our Mini Gym classes in the Summer term who are starting Reception in the September will automatically be placed on our waiting list for a Beginners Gymnastics class. Spaces are allocated on a random name-out-of-the-hat basis, and all will be offered a space by the Summer term.</p><p>We also have a second waiting list for those who attend a TJ\'s class but leave before the Summer term before starting Reception. Spaces will only be offered to this list when everyone on the first list has been offered a space. WE THEREFORE SADLY CANNOT OFFER ANYONE A GYMNASTICS CLASS WHO HAS NOT ATTENDED OUR PRESCHOOL PROGRAMME.</p>',
        'attributes' => array(
            array(
                'label' => 'Class Day',
                'options' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday'),
            ),
            array(
                'label' => 'Time Slot',
                'options' => array('4:00 – 4:45', '4:30 – 5:15', '5:00 – 6:00', '4:45 – 5:30', '5:30 – 6:30', '4:30 – 5:30', '4:45 – 5:45', '4:45 – 6:45'),
            ),
            array(
                'label' => 'Group Level',
                'options' => array('Mixed Intermediate', 'Girls Mini Squad', 'Boys Elite', 'Beginners', 'Mixed Squad', 'Girls Squad', 'Girls Junior Squad', 'Girls Elite'),
            ),
        ),
        'acf_field_keys' => $gym_field_keys,
        'acf_values' => array(
            'ct_season' => 'Summer 2026',
            'ct_status' => 'Teaching now',
            'ct_weeks' => '13 weeks',
            'ct_dates' => "13 Apr – 21 May\n1 Jun – 16 Jul",
            'ct_halfterm' => 'Half term: w/k 25 May · No class 4 May',
            'ct_payment_due' => 'Payment due by 12 March',
            'nt1_season' => 'Winter 2026',
            'nt1_status' => 'Next term',
            'nt1_weeks' => '12 weeks',
            'nt1_dates' => "7 Sep – 15 Oct\n2 Nov – 10 Dec",
            'nt1_halfterm' => '2-week half term: w/k 19 October',
            'nt1_payment_due' => 'Payment due by 26 June',
            'nt2_season' => 'Spring 2027',
            'nt2_status' => 'Planning ahead',
            'nt2_weeks' => '11 weeks',
            'nt2_dates' => "4 Jan – 11 Feb\n22 Feb – 25 Mar",
            'nt2_halfterm' => 'Half term: w/k 15 February',
            'nt2_payment_due' => 'Payment due by 27 November',
            'age_range' => '5+ Years',
            'about_title' => '<em>Progressive</em> gymnastics for children aged 5+',
            'about_content' => '<p>Our Gymnastics classes are for children aged 5 years. Sessions are built around floor and vault work, helping gymnasts develop confidence, technique, body control and strength in a structured but encouraging environment.</p><p>We use our own badge progression system, moving from Levels 7 to 1 and then on to Bronze, Silver, Gold, Platinum and Diamond awards.</p><p>We are limited with times and space as we accommodate a large age range after school. Children need to have attended a preschool class to be eligible for our Gymnastics classes. Anyone attending our Mini Gym classes in the Summer term who are starting Reception in the September will automatically be placed on our waiting list for a Beginners Gymnastics class. Spaces are allocated on a random name-out-of-the-hat basis, and all will be offered a space by the Summer term.</p><p>We also have a second waiting list for those who attend a TJ\'s class but leave before the Summer term before starting Reception. Spaces will only be offered to this list when everyone on the first list has been offered a space. WE THEREFORE SADLY CANNOT OFFER ANYONE A GYMNASTICS CLASS WHO HAS NOT ATTENDED OUR PRESCHOOL PROGRAMME.</p>',
            'pay_type' => 'per_term',
            'enable_trial' => 0,
            'trial_price' => '',
        ),
        'sessions' => array(
            array('sku' => 'GYM-MON-1600-MIXED', 'day' => 'Monday', 'time' => '4:00 – 4:45', 'group' => 'Mixed Intermediate', 'price' => 180, 'stock' => 3, 'capacity' => 12),
            array('sku' => 'GYM-MON-1630-GIRLSMINI', 'day' => 'Monday', 'time' => '4:30 – 5:15', 'group' => 'Girls Mini Squad', 'price' => 180, 'stock' => 2, 'capacity' => 10),
            array('sku' => 'GYM-MON-1700-BOYSELITE', 'day' => 'Monday', 'time' => '5:00 – 6:00', 'group' => 'Boys Elite', 'price' => 192, 'stock' => 1, 'capacity' => 8),
            array('sku' => 'GYM-TUE-1600-BEGINNERS', 'day' => 'Tuesday', 'time' => '4:00 – 4:45', 'group' => 'Beginners', 'price' => 195, 'stock' => 4, 'capacity' => 12),
            array('sku' => 'GYM-TUE-1645-MIXED', 'day' => 'Tuesday', 'time' => '4:45 – 5:30', 'group' => 'Mixed Intermediate', 'price' => 195, 'stock' => 2, 'capacity' => 12),
            array('sku' => 'GYM-TUE-1730-MIXEDSQUAD', 'day' => 'Tuesday', 'time' => '5:30 – 6:30', 'group' => 'Mixed Squad', 'price' => 208, 'stock' => 2, 'capacity' => 10),
            array('sku' => 'GYM-WED-1600-BEGINNERS', 'day' => 'Wednesday', 'time' => '4:00 – 4:45', 'group' => 'Beginners', 'price' => 195, 'stock' => 3, 'capacity' => 12),
            array('sku' => 'GYM-WED-1630-GIRLSSQUAD', 'day' => 'Wednesday', 'time' => '4:30 – 5:30', 'group' => 'Girls Squad', 'price' => 208, 'stock' => 2, 'capacity' => 10),
            array('sku' => 'GYM-THU-1600-BEGINNERS', 'day' => 'Thursday', 'time' => '4:00 – 4:45', 'group' => 'Beginners', 'price' => 195, 'stock' => 2, 'capacity' => 12),
            array('sku' => 'GYM-THU-1645-GIRLSJUNIOR', 'day' => 'Thursday', 'time' => '4:45 – 5:45', 'group' => 'Girls Junior Squad', 'price' => 208, 'stock' => 1, 'capacity' => 10),
            array('sku' => 'GYM-THU-1645-GIRLSELITE', 'day' => 'Thursday', 'time' => '4:45 – 6:45', 'group' => 'Girls Elite', 'price' => 253, 'stock' => 0, 'capacity' => 8),
        ),
    ),
);

$existing_product_menu_order = array(
    'tiddler-gym' => 10,
    'toddler-gym-product' => 20,
);

foreach ($existing_product_menu_order as $slug => $menu_order) {
    $existing_product_id = tjs_seed_find_product_id_by_slug($slug);
    if ($existing_product_id > 0) {
        wp_update_post(array(
            'ID' => $existing_product_id,
            'menu_order' => $menu_order,
        ));
    }
}

$results = array();

foreach ($specs as $spec) {
    $results[] = tjs_seed_ensure_variable_product($spec);
}

foreach ($results as $result) {
    printf(
        "Seeded product #%d with %d variations.\n",
        $result['product_id'],
        count($result['variation_ids'])
    );
}

echo "Mini Gym and Gymnastics products are ready.\n";