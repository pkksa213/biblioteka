<?php
/** Funkcje motywu; dane wpisów pozostają w standardowych tabelach WordPressa. */
if (!defined('ABSPATH')) { exit; }
define('BR_THEME_VERSION', '1.0.1');

require_once get_template_directory() . '/inc/data.php';
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/settings.php';
require_once get_template_directory() . '/inc/editor.php';
require_once get_template_directory() . '/inc/home.php';

add_action('after_setup_theme', function () {
    load_theme_textdomain('biblioteka-ranizow', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets'));
    add_theme_support('custom-logo', array('height' => 100, 'width' => 280, 'flex-width' => true, 'flex-height' => true));
    add_theme_support('editor-styles');
    add_editor_style('assets/editor.css');
    add_theme_support('align-wide');
    register_nav_menus(array('primary' => 'Menu główne biblioteki'));
    add_image_size('br-card', 720, 440, true);
    add_image_size('br-book', 300, 420, false);
});
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('br-theme', get_stylesheet_uri(), array(), BR_THEME_VERSION);
    wp_enqueue_style('br-site', get_template_directory_uri() . '/assets/site.css', array('br-theme'), BR_THEME_VERSION);
    wp_enqueue_script('br-site', get_template_directory_uri() . '/assets/site.js', array(), BR_THEME_VERSION, true);
});

function br_icon($name = 'book') {
    $paths = array(
        'book' => '<path d="M24 12C17 7 10 7 4 9v29c7-2 14-1 20 4 6-5 13-6 20-4V9c-6-2-13-2-20 3Zm0 0v30M11 16l7 2m-7 6 7 2m12-8 7-2m-7 10 7-2"/>',
        'arrow' => '<path d="M10 38 38 10M10 10h28v28"/>',
    );
    return '<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">' . (isset($paths[$name]) ? $paths[$name] : $paths['book']) . '</svg>';
}
function br_phone_url($phone) { return 'tel:' . preg_replace('/[^0-9+]/', '', $phone); }
function br_anchor($id) { return home_url('/#' . sanitize_title($id)); }
function br_page_url($key, $fallback = '') {
    $pages = get_option('br_pages', array());
    $id = isset($pages[$key]) ? absint($pages[$key]) : 0;
    if ($id && get_post_status($id) === 'publish') { return get_permalink($id); }
    return br_anchor($fallback ?: 'dla-czytelnikow');
}
function br_published_page($key) {
    $pages = get_option('br_pages', array());
    $id = isset($pages[$key]) ? absint($pages[$key]) : 0;
    return $id && get_post_status($id) === 'publish' ? $id : 0;
}
function br_reader_anchor($anchor) {
    $id = br_published_page('reader');
    if (!$id) { return br_anchor('dla-czytelnikow'); }
    return get_permalink($id) . (get_page_template_slug($id) === 'template-reader.php' ? '#' . sanitize_title($anchor) : '');
}
function br_document_url($key) { return br_published_page($key) ? br_page_url($key) : br_reader_anchor('dokumenty'); }
function br_blog_url() {
    $id = absint(get_option('page_for_posts'));
    return $id && get_post_status($id) === 'publish' ? get_permalink($id) : br_anchor('aktualnosci');
}
function br_term_id($key) {
    $terms = get_option('br_categories', array());
    if (!empty($terms[$key]) && term_exists((int) $terms[$key], 'category')) { return (int) $terms[$key]; }
    $definitions = br_category_definitions();
    if (!isset($definitions[$key])) { return 0; }
    $term = get_term_by('slug', $definitions[$key]['slug'], 'category');
    if (!$term) { $term = get_term_by('name', $definitions[$key]['name'], 'category'); }
    return $term ? (int) $term->term_id : 0;
}
function br_category_url($key) {
    $id = br_term_id($key);
    $url = $id ? get_category_link($id) : false;
    return $url && !is_wp_error($url) ? $url : br_blog_url();
}
function br_query($args = array()) {
    return new WP_Query(array_merge(array('post_type' => 'post', 'post_status' => 'publish', 'ignore_sticky_posts' => true, 'no_found_rows' => true), $args));
}
function br_gallery_ids() {
    $id = br_published_page('gallery');
    if (!$id) { return array(); }
    $ids = array();
    $walk = function ($blocks) use (&$walk, &$ids) {
        foreach ($blocks as $block) {
            if ($block['blockName'] === 'core/image' && !empty($block['attrs']['id'])) { $ids[] = absint($block['attrs']['id']); }
            if ($block['blockName'] === 'core/gallery' && !empty($block['attrs']['ids'])) { $ids = array_merge($ids, array_map('absint', $block['attrs']['ids'])); }
            if (!empty($block['innerBlocks'])) { $walk($block['innerBlocks']); }
        }
    };
    $walk(parse_blocks(get_post_field('post_content', $id)));
    return array_slice(array_values(array_unique(array_filter($ids))), 0, 6);
}

/** Natywne podmenu details: obsługa kliknięciem i klawiaturą, także bez JS. */
class BR_Menu_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) { $output .= '<div class="submenu"><ul>'; }
    public function end_lvl(&$output, $depth = 0, $args = null) { $output .= '</ul></div>'; }
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $children = in_array('menu-item-has-children', (array) $item->classes, true) && $depth === 0;
        $catalog = in_array('br-catalog-menu', (array) $item->classes, true);
        $output .= '<li class="nav-item' . ($catalog ? ' br-catalog-menu' : '') . '">';
        if ($children) { $output .= '<details class="nav-group"><summary>' . esc_html($item->title) . '</summary>'; }
        else { $output .= '<a href="' . esc_url($item->url) . '"' . ($item->target === '_blank' ? ' target="_blank" rel="noopener noreferrer"' : '') . '>' . esc_html($item->title) . '</a>'; }
    }
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if (in_array('menu-item-has-children', (array) $item->classes, true) && $depth === 0) { $output .= '</details>'; }
        $output .= '</li>';
    }
}
function br_fallback_menu() {
    echo '<ul id="menu" class="nav wrap">';
    foreach (br_menu_definition() as $item) {
        echo '<li class="nav-item' . (!empty($item['catalog']) ? ' br-catalog-menu' : '') . '">';
        if (!empty($item['children'])) {
            echo '<details class="nav-group"><summary>' . esc_html($item['title']) . '</summary><div class="submenu"><ul>';
            foreach ($item['children'] as $child) { echo '<li><a href="' . esc_url($child['url']) . '">' . esc_html($child['title']) . '</a></li>'; }
            echo '</ul></div></details>';
        } else { echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>'; }
        echo '</li>';
    }
    echo '</ul>';
}

add_action('wp_head', function () {
    if (is_search() || is_404()) { return; }
    if (!defined('WPSEO_VERSION') && !defined('RANK_MATH_VERSION') && !defined('THE_SEO_FRAMEWORK_VERSION')) {
        $description = is_singular() && !is_front_page() ? wp_trim_words(wp_strip_all_tags(get_the_excerpt(get_queried_object_id())), 28, '') : 'Gminna Biblioteka Publiczna w Raniżowie. Godziny otwarcia, kontakt, katalog książek i filie w Woli Raniżowskiej oraz Mazurach.';
        if ($description) { echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n"; }
    }
    if (is_front_page()) {
        $data = br_data();
        $schema = array('@context' => 'https://schema.org', '@type' => 'Library', 'name' => 'Gminna Biblioteka Publiczna w Raniżowie', 'url' => home_url('/'), 'telephone' => $data['phone'], 'email' => $data['email'], 'address' => array('@type' => 'PostalAddress', 'streetAddress' => $data['street'], 'postalCode' => $data['postal'], 'addressLocality' => 'Raniżów', 'addressCountry' => 'PL'));
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}, 5);
