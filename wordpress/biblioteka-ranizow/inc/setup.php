<?php
if (!defined('ABSPATH')) { exit; }

function br_find_page($slug, $title) {
    $page = get_page_by_path($slug, OBJECT, 'page');
    if ($page && $page->post_status !== 'trash') { return $page; }
    $found = get_posts(array('post_type' => 'page', 'post_status' => array('publish', 'draft', 'pending', 'private'), 'title' => $title, 'posts_per_page' => 1));
    return $found ? $found[0] : null;
}
function br_prepare_site() {
    if (!current_user_can('manage_options')) { return new WP_Error('forbidden', 'Tylko administrator może przygotować stronę.'); }
    $categories = get_option('br_categories', array());
    foreach (br_category_definitions() as $key => $definition) {
        $term = get_term_by('slug', $definition['slug'], 'category');
        if (!$term) { $term = get_term_by('name', $definition['name'], 'category'); }
        if ($term) { $categories[$key] = (int) $term->term_id; }
        else {
            $created = wp_insert_term($definition['name'], 'category', array('slug' => $definition['slug']));
            if (is_wp_error($created)) { return $created; }
            $categories[$key] = (int) $created['term_id'];
        }
    }
    update_option('br_categories', $categories);
    $drafts = require get_template_directory() . '/inc/drafts.php';
    $definitions = array(
        'front' => array('slug' => 'strona-glowna', 'title' => 'Strona główna', 'content' => ''),
        'posts' => array('slug' => 'aktualnosci', 'title' => 'Aktualności', 'content' => ''),
        'reader' => array('slug' => 'dla-czytelnikow', 'title' => 'Dla czytelników', 'template' => 'template-reader.php', 'content' => ''),
        'about' => array('slug' => 'o-bibliotece', 'title' => 'O bibliotece', 'content' => '<p>Gminna Biblioteka Publiczna w Raniżowie oraz filie w Woli Raniżowskiej i Mazurach. Informacje o godzinach, adresach i katalogach znajdziesz na stronie głównej.</p>'),
        'branches' => array('slug' => 'filie', 'title' => 'Filie', 'template' => 'template-branches.php', 'content' => ''),
        'contact' => array('slug' => 'kontakt', 'title' => 'Kontakt', 'template' => 'template-contact.php', 'content' => ''),
        'gallery' => array('slug' => 'galeria', 'title' => 'Galeria', 'content' => ''),
        'rules' => array('slug' => 'regulamin', 'title' => 'Regulamin', 'draft' => true, 'content' => $drafts['rules']),
        'accessibility' => array('slug' => 'deklaracja-dostepnosci', 'title' => 'Deklaracja dostępności', 'draft' => true, 'content' => $drafts['accessibility']),
        'privacy' => array('slug' => 'prywatnosc-i-cookies', 'title' => 'Prywatność i cookies', 'draft' => true, 'content' => $drafts['privacy']),
        'statute' => array('slug' => 'statut', 'title' => 'Statut', 'draft' => true, 'content' => '<p>Szkic roboczy. Wstaw zatwierdzony, obowiązujący statut biblioteki przed publikacją.</p>'),
        'children_safety' => array('slug' => 'ochrona-maloletnich', 'title' => 'Ochrona małoletnich', 'draft' => true, 'content' => '<p>Szkic roboczy. Wstaw zatwierdzone standardy ochrony małoletnich właściwe dla biblioteki przed publikacją.</p>'),
    );
    $pages = get_option('br_pages', array());
    foreach ($definitions as $key => $definition) {
        $id = isset($pages[$key]) ? absint($pages[$key]) : 0;
        if ($id && get_post_type($id) === 'page' && get_post_status($id) !== 'trash') { continue; }
        $existing_reading = $key === 'front' ? absint(get_option('page_on_front')) : ($key === 'posts' ? absint(get_option('page_for_posts')) : 0);
        $page = $existing_reading && get_post_type($existing_reading) === 'page' && get_post_status($existing_reading) !== 'trash' ? get_post($existing_reading) : br_find_page($definition['slug'], $definition['title']);
        $new = !$page;
        // Obrazy i bloki bez tekstu też są treścią; nigdy nie traktuj ich jak pustej strony.
        $empty = $page && trim($page->post_content) === '' && !get_post_meta($page->ID, '_br_managed', true);
        if ($new) {
            $id = wp_insert_post(array('post_type' => 'page', 'post_status' => !empty($definition['draft']) ? 'draft' : 'publish', 'post_title' => $definition['title'], 'post_name' => $definition['slug'], 'post_content' => wp_kses_post($definition['content'])), true);
            if (is_wp_error($id)) { return $id; }
        } else {
            $id = $page->ID;
            // Legal documents remain drafts unless the user has already published them.
            if ($empty && empty($definition['draft'])) {
                $result = wp_update_post(array('ID' => $id, 'post_status' => 'publish', 'post_content' => wp_kses_post($definition['content'])), true);
                if (is_wp_error($result)) { return $result; }
            }
            elseif ($empty && !empty($definition['draft']) && $page->post_status !== 'publish') {
                $result = wp_update_post(array('ID' => $id, 'post_content' => wp_kses_post($definition['content'])), true);
                if (is_wp_error($result)) { return $result; }
            }
        }
        if ($new || $empty) {
            if (!empty($definition['template']) && empty($definition['draft'])) { update_post_meta($id, '_wp_page_template', $definition['template']); }
            update_post_meta($id, '_br_managed', 1);
        }
        $pages[$key] = (int) $id;
    }
    update_option('br_pages', $pages);
    update_option('show_on_front', 'page');
    update_option('page_on_front', $pages['front']);
    update_option('page_for_posts', $pages['posts']);
    if (!get_option('timezone_string')) { update_option('timezone_string', 'Europe/Warsaw'); }
    if (!has_nav_menu('primary')) {
        $menu_id = absint(get_option('br_menu_id'));
        if (!$menu_id || !wp_get_nav_menu_object($menu_id)) { $menu_id = wp_create_nav_menu('Biblioteka — menu główne'); }
        if (is_wp_error($menu_id)) { return $menu_id; }
        if (!wp_get_nav_menu_items($menu_id)) {
            foreach (br_menu_definition() as $definition) {
                $parent = wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $definition['title'], 'menu-item-url' => isset($definition['url']) ? $definition['url'] : '#', 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-classes' => !empty($definition['catalog']) ? 'br-catalog-menu' : ''));
                if (is_wp_error($parent)) { return $parent; }
                if (!empty($definition['children'])) {
                    foreach ($definition['children'] as $child) {
                        $result = wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $child['title'], 'menu-item-url' => $child['url'], 'menu-item-status' => 'publish', 'menu-item-type' => 'custom', 'menu-item-parent-id' => $parent));
                        if (is_wp_error($result)) { return $result; }
                    }
                }
            }
        }
        $locations = get_theme_mod('nav_menu_locations', array());
        $locations['primary'] = (int) $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
        update_option('br_menu_id', $menu_id);
    }
    update_option('br_setup_done', 1);
    return $pages;
}
add_action('admin_menu', function () { add_theme_page('Uruchom bibliotekę', 'Uruchom bibliotekę', 'manage_options', 'br-start', 'br_start_page'); });
function br_start_page() {
    if (!current_user_can('manage_options')) { wp_die('Brak uprawnień.'); }
    echo '<div class="wrap"><h1>Uruchom stronę biblioteki</h1>';
    if (isset($_GET['prepared'])) { echo '<div class="notice notice-success"><p>Strona przygotowana. <a href="' . esc_url(home_url('/')) . '">Zobacz bibliotekę</a>.</p></div>'; }
    echo '<p>Przygotowanie utworzy brakujące strony i kategorie, opublikuje puste strony informacyjne oraz ustawi stronę główną i listę wpisów. Istniejące treści pozostaną zachowane. Regulamin, deklaracja dostępności i pozostałe dokumenty prawne będą szkicami do uzupełnienia.</p><form method="post" action="' . esc_url(admin_url('admin-post.php')) . '"><input type="hidden" name="action" value="br_prepare">';
    wp_nonce_field('br_prepare');
    submit_button('Przygotuj stronę biblioteki');
    echo '</form><h2>Jak dodawać treści?</h2><ol><li>Otwórz <strong>Wpisy → Dodaj nowy</strong>.</li><li>W prawej zakładce <strong>Wpis</strong> rozwiń <strong>Treść biblioteki</strong> i wybierz artykuł, książkę, wydarzenie lub ważne ogłoszenie.</li><li>Dodaj tekst i zdjęcie wyróżniające. Uzupełnij pola wybranego rodzaju treści, a następnie opublikuj.</li></ol><p>Godziny, telefon, adres i katalogi zmienisz w <a href="' . esc_url(admin_url('admin.php?page=br-library')) . '">Dane biblioteki</a>. Galerię edytujesz zwykłym blokiem Galeria na stronie Galeria. Motyw nie dodaje przykładowych wpisów.</p></div>';
}
add_action('admin_post_br_prepare', function () {
    if (!current_user_can('manage_options')) { wp_die('Brak uprawnień.', '', array('response' => 403)); }
    check_admin_referer('br_prepare');
    $result = br_prepare_site();
    if (is_wp_error($result)) { wp_die(esc_html($result->get_error_message())); }
    wp_safe_redirect(admin_url('themes.php?page=br-start&prepared=1'));
    exit;
});
add_action('admin_notices', function () {
    if (current_user_can('manage_options') && !get_option('br_setup_done')) { echo '<div class="notice notice-info"><p>Motyw Biblioteka Raniżów jest aktywny. <a href="' . esc_url(admin_url('themes.php?page=br-start')) . '">Kliknij tutaj, aby przygotować strony i menu</a>.</p></div>'; }
});
