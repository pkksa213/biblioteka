<?php
if (!defined('ABSPATH')) { exit; }

function br_kind($value) { return in_array($value, array('article', 'book', 'event', 'notice'), true) ? $value : 'article'; }
function br_timestamp($value) { return is_numeric($value) && $value > 0 ? (int) $value : 0; }
function br_parse_datetime($value) {
    if (!is_string($value) || !$value) { return 0; }
    $date = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $value, wp_timezone());
    return $date && $date->format('Y-m-d\TH:i') === $value ? $date->getTimestamp() : 0;
}
add_action('init', function () {
    $fields = array('_br_kind' => array('string', 'br_kind'), '_br_book_author' => array('string', 'sanitize_text_field'), '_br_book_catalog' => array('string', function ($url) { return esc_url_raw($url, array('http', 'https')); }), '_br_event_place' => array('string', 'sanitize_text_field'), '_br_event_start' => array('integer', 'br_timestamp'), '_br_event_until' => array('integer', 'br_timestamp'), '_br_notice_until' => array('integer', 'br_timestamp'));
    foreach ($fields as $key => $definition) {
        register_post_meta('post', $key, array('type' => $definition[0], 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => $definition[1], 'auth_callback' => function ($allowed, $meta_key, $post_id) { return current_user_can('edit_post', $post_id); }));
    }
});
add_action('add_meta_boxes_post', function ($post) {
    if (function_exists('use_block_editor_for_post') && use_block_editor_for_post($post)) { return; }
    add_meta_box('br-content', 'Treść biblioteki', 'br_content_box', 'post', 'normal', 'high');
});
add_action('enqueue_block_editor_assets', function () {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'post') {
        wp_enqueue_script('br-block-editor', get_template_directory_uri() . '/assets/block-editor.js', array('wp-plugins', 'wp-edit-post', 'wp-editor', 'wp-element', 'wp-components', 'wp-data', 'wp-date'), BR_THEME_VERSION, true);
    }
});
function br_content_box($post) {
    $kind = br_kind(get_post_meta($post->ID, '_br_kind', true));
    wp_nonce_field('br_content', 'br_content_nonce');
    echo '<p><label for="br-kind"><strong>Rodzaj treści</strong></label><br><select name="br_kind" id="br-kind">';
    foreach (array('article' => 'Artykuł', 'book' => 'Nowa książka', 'event' => 'Wydarzenie', 'notice' => 'Ważne ogłoszenie') as $key => $label) { echo '<option value="' . esc_attr($key) . '" ' . selected($kind, $key, false) . '>' . esc_html($label) . '</option>'; }
    echo '</select></p><p>Artykuł pojawia się w aktualnościach. Książka trafia także do sekcji nowości, wydarzenie do kalendarza, a ważne ogłoszenie do żółtego bloku.</p>';
    $fields = array(
        'book' => array('br_book_author' => array('Autor książki', 'text', '_br_book_author'), 'br_book_catalog' => array('Link do książki w katalogu (opcjonalnie)', 'url', '_br_book_catalog')),
        'event' => array('br_event_start' => array('Data i godzina rozpoczęcia', 'datetime-local', '_br_event_start'), 'br_event_end' => array('Data i godzina zakończenia (opcjonalnie)', 'datetime-local', '_br_event_until'), 'br_event_place' => array('Miejsce wydarzenia', 'text', '_br_event_place')),
        'notice' => array('br_notice_until' => array('Wyświetlaj ogłoszenie do (opcjonalnie)', 'datetime-local', '_br_notice_until')),
    );
    foreach ($fields as $group => $definitions) {
        echo '<div data-br-fields="' . esc_attr($group) . '">';
        foreach ($definitions as $name => $definition) {
            $value = get_post_meta($post->ID, $definition[2], true);
            if ($definition[1] === 'datetime-local') { $value = $value ? wp_date('Y-m-d\TH:i', (int) $value, wp_timezone()) : ''; }
            echo '<p><label for="' . esc_attr($name) . '">' . esc_html($definition[0]) . '</label><br><input class="widefat" style="max-width:600px" type="' . esc_attr($definition[1]) . '" id="' . esc_attr($name) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '"></p>';
        }
        if ($group === 'event') { echo '<p>Czas według strefy ustawionej w WordPressie: <strong>' . esc_html(wp_timezone_string()) . '</strong>. Bez godziny zakończenia wydarzenie pozostaje w kalendarzu do końca dnia. Niekompletny termin wymaga poprawienia przed pojawieniem się w kalendarzu.</p>'; }
        if ($group === 'notice') { echo '<p>Puste pole oznacza brak terminu wygaśnięcia. Zaplanowane wpisy pojawią się dopiero po publikacji.</p>'; }
        echo '</div>';
    }
    echo '<p>Opis wpisz w głównym edytorze. Okładkę, zdjęcie lub plakat ustaw jako <strong>obrazek wyróżniający</strong>.</p>';
}
add_action('admin_enqueue_scripts', function ($hook) {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'post' && !$screen->is_block_editor() && in_array($hook, array('post.php', 'post-new.php'), true)) { wp_enqueue_script('br-editor', get_template_directory_uri() . '/assets/editor.js', array(), BR_THEME_VERSION, true); }
});
function br_save_content($post_id) {
    if (!isset($_POST['br_content_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['br_content_nonce'])), 'br_content')) { return; }
    if (!current_user_can('edit_post', $post_id) || wp_is_post_revision($post_id) || wp_is_post_autosave($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) { return; }
    $read = function ($key) { return isset($_POST[$key]) && is_scalar($_POST[$key]) ? wp_unslash($_POST[$key]) : ''; };
    $kind = br_kind($read('br_kind'));
    update_post_meta($post_id, '_br_kind', $kind);
    if ($kind === 'book') {
        update_post_meta($post_id, '_br_book_author', sanitize_text_field($read('br_book_author')));
        update_post_meta($post_id, '_br_book_catalog', esc_url_raw($read('br_book_catalog'), array('http', 'https')));
    }
    if ($kind === 'event') { update_post_meta($post_id, '_br_event_place', sanitize_text_field($read('br_event_place'))); }
    $start = br_parse_datetime($read('br_event_start'));
    $end = br_parse_datetime($read('br_event_end'));
    if ($start && !$end && !$read('br_event_end')) { $end = (new DateTimeImmutable('@' . $start))->setTimezone(wp_timezone())->setTime(23, 59, 59)->getTimestamp(); }
    if ($kind === 'event' && $start && $end >= $start) { update_post_meta($post_id, '_br_event_start', $start); update_post_meta($post_id, '_br_event_until', $end); }
    elseif ($kind === 'event') {
        delete_post_meta($post_id, '_br_event_start'); delete_post_meta($post_id, '_br_event_until');
        set_transient('br_editor_notice_' . get_current_user_id(), 'Uzupełnij prawidłowy termin wydarzenia. Bez niego wpis nie pojawi się w kalendarzu.', 120);
    }
    $expiry = br_parse_datetime($read('br_notice_until'));
    if ($kind === 'notice') { update_post_meta($post_id, '_br_notice_until', $expiry); }
    $category = $kind === 'event' ? 'events' : ($kind === 'notice' ? 'notices' : '');
    if ($kind === 'book' && !has_category(array(br_term_id('adults'), br_term_id('children')), $post_id)) { $category = 'adults'; }
    if ($category && br_term_id($category)) { wp_set_post_categories($post_id, array(br_term_id($category)), true); }
}
add_action('save_post_post', 'br_save_content');

// Klasyczny edytor i szybka edycja także nie publikują wydarzenia bez terminu.
add_filter('wp_insert_post_data', function ($data, $postarr) {
    if ($data['post_type'] !== 'post' || !in_array($data['post_status'], array('publish', 'future'), true) || (defined('REST_REQUEST') && REST_REQUEST)) { return $data; }
    $post_id = isset($postarr['ID']) ? absint($postarr['ID']) : 0;
    $kind = get_post_meta($post_id, '_br_kind', true);
    $valid_form = isset($_POST['br_content_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['br_content_nonce'])), 'br_content') && current_user_can('edit_post', $post_id);
    if ($valid_form && isset($_POST['br_kind'])) { $kind = br_kind(sanitize_text_field(wp_unslash($_POST['br_kind']))); }
    if ($kind !== 'event') { return $data; }
    $start = (int) get_post_meta($post_id, '_br_event_start', true);
    $end = (int) get_post_meta($post_id, '_br_event_until', true);
    if ($valid_form) {
        $start = isset($_POST['br_event_start']) ? br_parse_datetime(wp_unslash($_POST['br_event_start'])) : 0;
        $end = isset($_POST['br_event_end']) ? br_parse_datetime(wp_unslash($_POST['br_event_end'])) : 0;
        if (!empty($_POST['br_event_end']) && !$end) { $start = 0; }
    }
    if (!$start || ($end && $end < $start)) {
        $data['post_status'] = 'draft';
        set_transient('br_editor_notice_' . get_current_user_id(), 'Wydarzenie zapisano jako szkic. Uzupełnij prawidłowy termin przed publikacją.', 120);
    }
    return $data;
}, 10, 2);

add_filter('rest_pre_insert_post', function ($prepared, $request) {
    $meta = $request->get_param('meta');
    $meta = is_array($meta) ? $meta : array();
    $post_id = absint($request->get_param('id'));
    $kind = isset($meta['_br_kind']) ? br_kind($meta['_br_kind']) : get_post_meta($post_id, '_br_kind', true);
    $status = isset($prepared->post_status) ? $prepared->post_status : get_post_status($post_id);
    if ($kind === 'event' && in_array($status, array('publish', 'future'), true)) {
        $start = isset($meta['_br_event_start']) ? br_timestamp($meta['_br_event_start']) : (int) get_post_meta($post_id, '_br_event_start', true);
        $end = isset($meta['_br_event_until']) ? br_timestamp($meta['_br_event_until']) : (int) get_post_meta($post_id, '_br_event_until', true);
        if (!$start || ($end && $end < $start)) { return new WP_Error('br_event_date', 'Uzupełnij prawidłowy termin wydarzenia. Zakończenie nie może być wcześniejsze niż rozpoczęcie.', array('status' => 400)); }
    }
    return $prepared;
}, 10, 2);
add_action('rest_after_insert_post', function ($post) {
    if (!current_user_can('edit_post', $post->ID)) { return; }
    $kind = get_post_meta($post->ID, '_br_kind', true);
    if ($kind === 'event') {
        $start = (int) get_post_meta($post->ID, '_br_event_start', true);
        $until = (int) get_post_meta($post->ID, '_br_event_until', true);
        if ($start && !$until) { update_post_meta($post->ID, '_br_event_until', (new DateTimeImmutable('@' . $start))->setTimezone(wp_timezone())->setTime(23, 59, 59)->getTimestamp()); }
    }
    $category = $kind === 'event' ? 'events' : ($kind === 'notice' ? 'notices' : '');
    if ($kind === 'book' && !has_category(array(br_term_id('adults'), br_term_id('children')), $post->ID)) { $category = 'adults'; }
    if ($category && br_term_id($category)) { wp_set_post_categories($post->ID, array(br_term_id($category)), true); }
}, 10, 1);
add_action('admin_notices', function () {
    $key = 'br_editor_notice_' . get_current_user_id();
    $notice = get_transient($key);
    if ($notice) { delete_transient($key); echo '<div class="notice notice-warning"><p>' . esc_html($notice) . '</p></div>'; }
});
