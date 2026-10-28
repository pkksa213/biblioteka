<?php
/** Wspólne zapytania sekcji strony głównej i diagnostyka administratora. */
if (!defined('ABSPATH')) { exit; }

function br_home_query_args($section) {
    $latest = array('orderby' => array('date' => 'DESC', 'ID' => 'DESC'));
    switch ($section) {
        case 'news':
            return array_merge($latest, array('posts_per_page' => 3, 'meta_query' => array('relation' => 'OR', array('key' => '_br_kind', 'compare' => 'NOT EXISTS'), array('key' => '_br_kind', 'value' => 'book', 'compare' => '!='))));
        case 'books':
            return array_merge($latest, array('posts_per_page' => 4, 'meta_key' => '_br_kind', 'meta_value' => 'book'));
        case 'events':
            // Opcjonalny koniec nie może być wymaganym warunkiem JOIN. Starsze lub
            // importowane wpisy mogą mieć tylko początek, bez wyliczonego końca dnia.
            $today = (new DateTimeImmutable('today', wp_timezone()))->getTimestamp();
            return array('posts_per_page' => 3, 'meta_key' => '_br_event_start', 'orderby' => array('meta_value_num' => 'ASC', 'ID' => 'DESC'), 'meta_query' => array(
                'relation' => 'AND',
                array('key' => '_br_kind', 'value' => 'event'),
                array('key' => '_br_event_start', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC'),
                array('relation' => 'OR',
                    array('key' => '_br_event_until', 'value' => time(), 'compare' => '>=', 'type' => 'NUMERIC'),
                    array('relation' => 'AND',
                        array('relation' => 'OR', array('key' => '_br_event_until', 'compare' => 'NOT EXISTS'), array('key' => '_br_event_until', 'value' => 0, 'type' => 'NUMERIC')),
                        array('key' => '_br_event_start', 'value' => $today, 'compare' => '>=', 'type' => 'NUMERIC')
                    )
                )
            ));
        case 'notices':
            return array_merge($latest, array('posts_per_page' => 3, 'meta_query' => array('relation' => 'AND', array('key' => '_br_kind', 'value' => 'notice'), array('relation' => 'OR', array('key' => '_br_notice_until', 'compare' => 'NOT EXISTS'), array('key' => '_br_notice_until', 'value' => 0, 'type' => 'NUMERIC'), array('key' => '_br_notice_until', 'value' => time(), 'type' => 'NUMERIC', 'compare' => '>=')))));
    }
    return array('post__in' => array(0));
}
function br_home_query($section) { return br_query(br_home_query_args($section)); }

function br_refresh_home() {
    $front_id = absint(get_option('page_on_front'));
    if ($front_id) { clean_post_cache($front_id); }
    wp_cache_set_posts_last_changed();
    // W3TC nie nasłuchuje clean_post_cache, a jego domyślna polityka pomija
    // statyczną stronę główną. Opróżnij jej HTML przez publiczne API wtyczki.
    if (function_exists('w3tc_flush_url')) { w3tc_flush_url(home_url('/')); }
}
// W REST save_post następuje przed zapisem pól. Powiadomienie o stronie
// zbierającej wpisy musi nastąpić po zapisaniu także rodzaju i terminu.
add_action('wp_after_insert_post', function ($post_id, $post, $update, $before) {
    if ($post->post_type === 'post' && ($post->post_status === 'publish' || ($before && $before->post_status === 'publish'))) { br_refresh_home(); }
}, 100, 4);
add_action('deleted_post', function ($post_id, $post) {
    if ($post->post_type === 'post' && $post->post_status === 'publish') { br_refresh_home(); }
}, 10, 2);
add_action('after_switch_theme', 'br_refresh_home');
add_action('admin_init', function () {
    if (current_user_can('manage_options') && get_option('br_home_version') !== BR_THEME_VERSION) {
        br_refresh_home();
        update_option('br_home_version', BR_THEME_VERSION);
    }
});

function br_home_diagnostic_report() {
    $lines = array('Diagnostyka strony głównej biblioteki', 'Czas: ' . wp_date('Y-m-d H:i:s T'), 'Adres: ' . home_url('/'), 'WordPress: ' . get_bloginfo('version'), 'Motyw: ' . get_stylesheet() . ' ' . BR_THEME_VERSION, 'Ustawienie Czytanie: ' . get_option('show_on_front'));
    foreach (array('page_on_front' => 'Strona główna', 'page_for_posts' => 'Lista wpisów') as $option => $label) {
        $id = absint(get_option($option));
        $lines[] = $label . ': ' . ($id ? '#' . $id . ' ' . get_the_title($id) . ' [' . get_post_status($id) . ']' : 'nie przypisano');
    }
    $front_id = absint(get_option('page_on_front'));
    $lines[] = 'Treść edytora strony głównej: ' . ($front_id && trim(get_post_field('post_content', $front_id)) !== '' ? 'jest dodatkowa treść' : 'pusta');
    $lines[] = 'Plik index.html obok WordPressa: ' . (is_file(ABSPATH . 'index.html') ? 'ISTNIEJE — sprawdź, czy nie zasłania index.php' : 'brak');
    $lines[] = 'WP_CACHE: ' . (defined('WP_CACHE') && WP_CACHE ? 'tak' : 'nie');
    $lines[] = 'Wtyczki: ' . implode(', ', get_option('active_plugins', array()));
    if (class_exists('W3TC\\Dispatcher')) {
        $w3tc = W3TC\Dispatcher::config();
        $lines[] = 'W3TC Page Cache: ' . ($w3tc->get_boolean('pgcache.enabled') ? 'tak' : 'nie');
        $lines[] = 'W3TC automatyczne czyszczenie Front page: ' . ($w3tc->get_boolean('pgcache.purge.front_page') ? 'tak' : 'nie');
        $lines[] = 'W3TC API odświeżania przez motyw: ' . (function_exists('w3tc_flush_url') ? 'dostępne' : 'brak');
    }
    $visible = array();
    foreach (array('news' => 'Aktualności', 'events' => 'Kalendarz', 'books' => 'Książki', 'notices' => 'Ogłoszenia') as $section => $label) {
        $query = br_home_query($section);
        $visible[$section] = wp_list_pluck($query->posts, 'ID');
        $lines[] = $label . ' — aktualnie pobierane ID: ' . ($visible[$section] ? implode(', ', $visible[$section]) : 'brak');
    }
    $lines[] = '';
    $lines[] = 'Ostatnie 20 wpisów (status, zapisany rodzaj, termin i sekcje):';
    $recent = br_query(array('posts_per_page' => 20, 'post_status' => array('publish', 'draft', 'future', 'pending', 'private'), 'orderby' => array('date' => 'DESC', 'ID' => 'DESC')));
    foreach ($recent->posts as $post) {
        $kind = get_post_meta($post->ID, '_br_kind', true);
        $areas = array();
        foreach ($visible as $section => $ids) { if (in_array($post->ID, $ids, true)) { $areas[] = $section; } }
        $lines[] = '#' . $post->ID . ' ' . wp_strip_all_tags($post->post_title) . ' | ' . $post->post_status . ' | rodzaj: ' . ($kind ?: '(brak; domyślnie artykuł)') . ' | sekcje: ' . ($areas ? implode(', ', $areas) : 'brak');
        if ($kind === 'event') {
            $start = (int) get_post_meta($post->ID, '_br_event_start', true);
            $end = (int) get_post_meta($post->ID, '_br_event_until', true);
            $lines[] = '  Początek: ' . ($start ? wp_date('Y-m-d H:i T', $start) : 'BRAK') . '; koniec: ' . ($end ? wp_date('Y-m-d H:i T', $end) : 'domyślnie koniec dnia');
        }
    }
    return implode("\n", $lines);
}
add_action('admin_menu', function () { add_theme_page('Sprawdź wpisy', 'Sprawdź wpisy', 'manage_options', 'br-posts', 'br_home_diagnostic_page'); });
function br_home_diagnostic_page() {
    if (!current_user_can('manage_options')) { wp_die('Brak uprawnień.', '', array('response' => 403)); }
    echo '<div class="wrap"><h1>Sprawdź wpisy na stronie głównej</h1><p>Raport pokazuje rzeczywiście pobierane wpisy oraz zapisane terminy. Nie zmienia treści ani ustawień. Jeśli ID wpisu znajduje się w odpowiedniej sekcji raportu, ale nie na stronie, porównaj świeży podgląd i sprawdź pamięć podręczną wtyczki lub hostingu.</p>';
    echo '<p><a class="button button-primary" href="' . esc_url(wp_nonce_url(home_url('/?br_home_check=1'), 'br_home_check')) . '">Otwórz świeży podgląd strony głównej</a></p>';
    echo '<p><label for="br-home-report">Raport do skopiowania — bez haseł i danych logowania:</label></p><textarea id="br-home-report" readonly rows="24" class="large-text code">' . esc_textarea(br_home_diagnostic_report()) . '</textarea></div>';
}
add_action('template_redirect', function () {
    if (!isset($_GET['br_home_check'])) { return; }
    if (!current_user_can('manage_options')) { wp_die('Zaloguj się jako administrator.', '', array('response' => 403)); }
    check_admin_referer('br_home_check');
    if (!defined('DONOTCACHEPAGE')) { define('DONOTCACHEPAGE', true); }
    nocache_headers();
    header('X-Robots-Tag: noindex, nofollow');
    add_action('wp_body_open', function () {
        echo '<p class="wrap empty-state" role="status">Podgląd administratora — motyw ' . esc_html(BR_THEME_VERSION) . ', wygenerowano ' . esc_html(wp_date('Y-m-d H:i:s T')) . '.</p>';
    });
}, 0);
